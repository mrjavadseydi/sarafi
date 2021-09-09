<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Sell;
use Illuminate\Http\Request;
use App\helper\GateWay;
class GateWayController extends Controller
{
    private $gateWay;
    public function __construct(){
        $this->gateWay = new GateWay();
    }

    public function init($id){
        $order = Sell::findOrFail($id);
        $user = Member::where('chat_id',$order->chat_id)->first();
        $param  = [
            'merchantCode'=>config('gateway.MERCHANT_CODE'),
            'amount'=>$order->price,
            'callBackUrl'=>route('gateway.callback'),
            'invoiceNumber'=>$id,
            'payerName' => $user->name,
//            'payerMobile' => $user->phone,
            'description' =>"خرید ".$order->amount." دلار پرفکت مانی",
        ];
        $request =$this->gateWay->paymentRequest($param);
//        dd($request);
        if($request['status']){
            Sell::whereId($id)->update([
                'paymentNumber'=>$request['paymentNumber']
            ]);
            $this->gateWay->paymentGateway($request['paymentNumber']);
        }else{
            return "خط در هنگام اتصال به درگاه";
        }
    }

    public function verify(Request $request){
        if(!$request->has('paymentNumber'))
            abort(403);

        $sell = Sell::where([['status',0],['paymentNumber',$request->paymentNumber]])->first();
        if(!$sell)
            abort(403);
        deleteMessage([
            'chat_id'=>$sell->chat_id,
            'message_id'=>$sell->message_id
        ]);
        $result = $this->gateWay->paymentVerify([
            'merchantCode'=>config('gateway.MERCHANT_CODE'),
            'paymentNumber'=>$request->paymentNumber
        ]);
        if($result['status']){
            sendMessage([
                'chat_id'=>$sell->chat_id,
                'text'=>'پرداخت شما با موفقیت انجام شد \n شماره پرداخت : '.$_POST['paymentNumber']."حداکثر تا ۱۲ ساعت ووچر شما ارسال خواهد شد",
                'reply_markup'=>backButton()
            ]);
            sendMessage([
                'chat_id'=>getConfig('residGroup'),
                'text'=>"پرداخت جدید توسط درگاه
 شماره پیگیری :".$request->paymentNumber."
".$sell->amount."مبلغ :
تعداد :".$sell->price." $
ایا مورد تایید است؟چر را دریافت کند؟",
                'reply_markup'=>acceptPay($sell->chat_id,$sell->id)

            ]);
            $sell->update([
                'status'=>1
            ]);
            nullState($sell->chat_id);
            return'پرداخت شما با موفقیت انجام شد <br /> شماره پرداخت : '.$_POST['paymentNumber'];

        }else{
            sendMessage([
                'chat_id'=>$sell->chat_id,
                'text'=>"پرداخت شما موفقیت آمیز نبود ! در صورت کسر وجه تا ۷۲ ساعت وجه به حساب شما بازگشت خواهد کرد",
                'reply_markup'=>backButton()
            ]);
            $sell->update([
                'status'=>-1
            ]);
            nullState($sell->chat_id);
            return "پرداخت شما موفقیت آمیز نبود ! در صورت کسر وجه تا ۷۲ ساعت وجه به حساب شما بازگشت خواهد کرد";
        }
    }
}
