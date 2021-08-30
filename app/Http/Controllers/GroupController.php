<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupController extends AdminSettingController
{
    public function setResidGroup(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"ایدی گروه را ارسال کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setResidGroup');
    }
    public function setResidGroupId(){
        setConfig('residGroup',$this->text);
        setState($this->chat_id,'adminMenu');
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"ایدی ثبت شد !",
            'reply_markup'=>setGroupButton()
        ]);
    }
    public function setValidationGroup(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"ایدی گروه را ارسال کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setValidationGroup');
    }
    public function setValidationGroupId(){
        setConfig('validationGroup',$this->text);
        setState($this->chat_id,'adminMenu');
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"ایدی ثبت شد !",
            'reply_markup'=>setGroupButton()
        ]);
    }
    public function setPayOutGroup(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"ایدی گروه را ارسال کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setPayOutGroup');
    }
    public function setPayOutGroupId(){
        setConfig('payOutGroup',$this->text);
        setState($this->chat_id,'adminMenu');
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"ایدی ثبت شد !",
            'reply_markup'=>setGroupButton()
        ]);
    }
}
