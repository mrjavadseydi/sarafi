<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use Illuminate\Support\Facades\Cache;


trait CallSell
{
    public function initSell($req){
        if($this->user->active == 0){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"احراز هویت شما انجام نشده! ",
                'reply_markup'=>activationButton()
            ]);
        }else if($this->user->active==1){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"مدارک شما در حال بررسی میباشد ",
                'reply_markup'=>backButton()
            ]);
        }else{
            setState($this->chat_id,'GiveSellCount');
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"لطفا مقدار مورد نظر را وارد کنید ! \n قیمت هر دلار امروز ".getConfig('sellPrice'),
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function receiveSellCount(){
        if(is_numeric($this->text)){
            $price =round(getConfig('sellPrice') * $this->text , 1);
            $sell = Sell::create([
                'chat_id'=>$this->chat_id,
                'amount'=>$this->text,
                'price'=>$price,
                'status'=>0
            ]);
            $text = "هزینه قابل پرداخت : $price \n جهت پرداخت روی دکمه زیر کلیک کنید ! \n پس از پرداخت ووچر برای شما ارسال خواهد شد";
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>$text,
                'reply_markup'=>payUrlButton($sell->id)
            ]);
            nullState($this->chat_id);
            $this->start();
        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"لطفا مقدار مورد نظر را با اعداد لاتین  وارد کنید ! \n قیمت هر دلار امروز ".getConfig('sellPrice'),
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function receiveResid($req){
        if ($this->message_type=="photo"){
            $photo = end($req["message"]['photo'])['file_id'];
            $sell_id = Cache::pull('sendResid'.$this->chat_id);
            $sell = Sell::whereId($sell_id)->first();
            $text = "[$this->chat_id](tg://user?id=$this->chat_id) \n".$this->user->name." \n".$this->user->phone." \n".number_format($sell->price)."\n $sell->amount \$
            ";

            sendPhoto([
                'chat_id'=>getConfig('residGroup'),
                'caption'=>$text,
                'photo'=>strval($photo),
                'parse_mode' => "Markdown",
            ]);
            sendMessage([
                'chat_id'=>getConfig('residGroup'),
                'text'=>"ایا تایید میکنید تا کاربر ووچر را دریافت کند؟",
                'reply_markup'=>acceptPay($this->chat_id,$sell_id)

            ]);
            $sell->update([
                'photo'=>$photo
            ]);
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"تصویر رسید برای مدیریت ارسال شد ، پس از تایید ووچر برای شما ارسال میشود!",
                'reply_markup'=>backButton()
            ]);
        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"تنها تصویر مورد قبول میباشد",
                'reply_markup'=>backButton()
            ]);
        }
    }
}
