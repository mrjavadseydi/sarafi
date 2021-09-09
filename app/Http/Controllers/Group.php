<?php

namespace App\Http\Controllers;



trait Group
{
    public function setResidGroup(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"ایدی گروه را ارسال کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setResidGroupId');
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
        setState($this->chat_id,'setValidationGroupId');
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
        setState($this->chat_id,'setPayOutGroupId');
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
