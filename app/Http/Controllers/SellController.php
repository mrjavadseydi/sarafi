<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellController extends ActivationController
{
    public function initSell($req){
        if($this->user->active == 0){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"احراز هویت شما انجام نشده! ",
                'reply_markup'=>activationButton()
            ]);
        }else if($this->user->activate==1){
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
}
