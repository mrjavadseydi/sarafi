<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UtilityController;
trait  AdminSetting
{
    public function initAdmin(){
        setState($this->chat_id,"adminMenu");
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>'wellcome to admin Panel',
            'reply_markup'=>adminButton()
        ]);
    }
    public function setGroupMenu(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>'گروه مورد نظر را انتخاب کنید',
            'reply_markup'=>setGroupButton()
        ]);
    }
    public function setTextMenu(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>'بخش مورد نظر را انتخاب کنید',
            'reply_markup'=>setTextButton()
        ]);
    }
}
