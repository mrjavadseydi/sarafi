<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use Illuminate\Support\Facades\Cache;


trait CallBuy
{
    public function initBuy(){
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
            setState($this->chat_id,'GiveVoocherKey');
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"لطفا کد وچر خود را ارسال کنید ! \n قیمت خرید هر دلار امروز ".getConfig('buyPrice'),
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function receiveVoocherKey(){
        if(strlen($this->text)==10){
            $buy = Buy::create([
                'chat_id'=>$this->chat_id,
                'voocher'=>$this->text,
                'status'=>-2
            ]);
            Cache::put('getActivator'.$this->chat_id,$buy->id);
            setState($this->chat_id,'getActivator');
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"کد فعال ساز ۱۶ رقمی را ارسال کنید، توجه داشته باشید در صورت ارسال کد فعال ساز صحیح ، ووچر شما ثبت و در حساب ما دریافت خواهد شد ",
                'reply_markup'=>backButton()
            ]);
        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"کد ووچر ۱۰ رقم میباشد لطفا مجددا تلاش کنید",
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function receiveActivator(){
        if(strlen($this->text)==16){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"در حال دریافت اطلاعات ووچر و ثبت در حساب !لطفا صبر کنید "
            ]);
            $buy_id = Cache::pull('getActivator'.$this->chat_id);
            $buy = Buy::whereId($buy_id)->first();

            $result = ActiveVoucher($buy->voocher,$this->text);
            if (!$result){
                $buy->update([
                    'activator'=>$this->text,
                    'status'=>-1
                ]);
                sendMessage([
                    'chat_id'=>$this->chat_id,
                    'text'=>"اطلاعات ووچر شما غلط میباشد ! میتوانید مجدد تلاش کنید و یا با پشتیبانی در ارتباط باشید ! \n کد پیگیری : $buy_id"
                ]);
            }else{
                $price = $result * getConfig('buyPrice');
                $buy->update([
                    'activator'=>$this->text,
                    'amount'=>$result,
                    'price'=>$price,
                    'status'=>1
                ]);
                sendMessage([
                    'chat_id'=>$this->chat_id,
                    'text'=>"ووچر شما با موفقیت ثبت شد ! مبلغ $price  به شماره کارت ".$this->user->card."  به نام ".$this->user->name." ظرف مدت ۴۸ ساعت کاری واریز خواهد شد !\n کد پیگیری شما :$buy_id",
                    'reply_markup'=>backButton()
                ]);
                $text = "یک خرید موفق \n تعداد : $result \n مبلغ : ".number_format($price)." \n لطفا به شماره کارت  ".$this->user->card . " به نام ".$this->user->name ." واریز شود \n شبا :".$this->user->shaba ;
                sendMessage([
                    'chat_id'=>getConfig('payOutGroup'),
                    'text'=>$text,
                    'reply_markup'=>paidButton($this->chat_id,$buy_id)
                ]);
            }
            $this->start();

        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"کد فعالساز ۱۶ رقم میباشد لطفا مجددا تلاش کنید",
                'reply_markup'=>backButton()
            ]);
        }
    }
}
