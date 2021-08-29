<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Sell;
use Illuminate\Http\Request;

class  CallBackQueryController extends SellController
{
    public function initCallBack($req){
        $data = $req["callback_query"]['data'];
        $chat_id = $req["callback_query"]['message']['chat']['id'];
        $message_id = $req["callback_query"]["message"]['message_id'];
        $text = $req["callback_query"]['message']['text'];
        $ex = explode('-',$data);
        switch ($ex[0]){
            case 'activate':
                $this->confirmValidation($ex[1],$ex[2]);
                editMessageText([
                    'chat_id'=>$chat_id,
                    'text'=>$text . "\n در " .now()."تایید شد",
                    'message_id'=>$message_id
                ]);
                break;
            case "deactive":
                $this->denyValidation($ex[1],$ex[2]);
                editMessageText([
                    'chat_id'=>$chat_id,
                    'text'=>$text . "\n در " .now()."رد  شد",
                    'message_id'=>$message_id
                ]);
                break;
            case "block":
                $this->blockUser($ex[2]);
                editMessageText([
                    'chat_id'=>$chat_id,
                    'text'=>$text . "\n در " .now()."بلاک شد",
                    'message_id'=>$message_id
                ]);
                break;

            case "pay":
                $this->payToUser($ex[1],$ex[2],$chat_id,$message_id,$text);
                break;

            case "notPay":
                $this->denyUserPay($ex[1],$ex[2]);
                editMessageText([
                    'chat_id'=>$chat_id,
                    'text'=>$text . "\n در " .now()."رد شد \n" ,
                    'message_id'=>$message_id
                ]);
                break;
            case "paid":
                $this->paidBuyVoocher($ex[1],$ex[2]);
                editMessageText([
                    'chat_id'=>$chat_id,
                    'text'=>$text . "\n در " .now()."واریز شد \n" ,
                    'message_id'=>$message_id
                ]);
                break;
        }
    }


    public function confirmValidation($id,$chat_id){
        Member::where('id',$id)->update([
            'active'=>2
        ]);
        sendMessage([
            'chat_id'=>$chat_id,
            'text'=>'مدارک شما با موفقیت تایید شد',
            'reply_markup'=>backButton()
        ]);
    }
    public function denyValidation($id,$chat_id){
        Member::where('id',$id)->update([
            'active'=>0
        ]);
        sendMessage([
            'chat_id'=>$chat_id,
            'text'=>'احراز هویت شما رد شد لطفا نسبت به ارسال  محدد مدارک اقدام نمایید',
            'reply_markup'=>backButton()
        ]);
    }
    public function blockUser($chat_id){
        Member::where('chat_id',$chat_id)->update([
            'block'=>true
        ]);
    }
    public function payToUser($id,$user_id,$chat_id,$message_id,$old_text){
        $sell = Sell::whereId($id)->first();
        $voocher = sendMoneyToVoucher($sell->amount);
        $sell->update([
            'status'=>1,
            'voocher'=>$voocher[3],
            'activator'=>$voocher[4],
        ]);
        $text = "
🎟 e-Voucher 🎟
e-Voucher #: ".$voocher[3]."
Activation code:  ".$voocher[4]."
Amount:  ".$sell->amount."$ 💵
EV";
        sendMessage([
            'chat_id'=>$user_id,
            'text'=>$text,
            'reply_markup'=>backButton()
        ]);
        editMessageText([
            'chat_id'=>$chat_id,
            'text'=>$old_text . "\n در " .now()."پرداخت شد \n" .$text,
            'message_id'=>$message_id
        ]);
    }
    public function denyUserPay($id,$chat_id){
        $sell = Sell::whereId($id)->first();
        $sell->update([
            'status'=>-1,
        ]);
        sendMessage([
            'chat_id'=>$chat_id,
            'text'=>"درخواست خرید شما لغو شد ! میتوانید با پشتیبانی در ارتباط باشید",
            'reply_markup'=>backButton()
        ]);

    }
    public function paidBuyVoocher($id,$chat_id){
        $buy =Buy::whereId($id)->first();
        $buy->update([
            'paid'=>true,
            'status'=>2
        ]);
        sendMessage([
            'chat_id'=>$chat_id,
            'text'=>"مبلغ $buy->price به حساب شما واریز شد \n با توجه اینکه واریز بصورت شبا بوده ،‌ممکن است تا ۷۲ ساعت واریز از طرف بانک به طول بکشد ، در صورت هرگونه مشکل میتوانید با پشتیبانی در ارتباط باشید  ",
            'reply_markup'=>backButton()
        ]);
    }
}
