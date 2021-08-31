<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GroupController;
trait Channel
{
    public function initChannel(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"ایدی کانال را بدون @ ارسال کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setChannel');
    }
    public function setChannel(){
        setConfig('channel',$this->text);
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"تنظیم شد !",
            'reply_markup'=>adminButton()
        ]);
        setState($this->chat_id,'adminMenu');

    }
}
