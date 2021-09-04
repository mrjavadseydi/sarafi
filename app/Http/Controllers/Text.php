<?php

namespace App\Http\Controllers;


trait Text
{
    public function setContactUs(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"متن  را وارد کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setContactUsText');
    }
    public function setContactUsText(){
        setConfig('contactUs',$this->text);
        setState($this->chat_id,'adminMenu');
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"متن ثبت شد !",
            'reply_markup'=>setTextButton()
        ]);
    }
    public function setFaq(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"متن  را وارد کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setFaqText');
    }
    public function setFaqText(){
        setConfig('faq',$this->text);
        setState($this->chat_id,'adminMenu');
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"متن ثبت شد !",
            'reply_markup'=>setTextButton()
        ]);
    }
    public function setHelp(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"متن  را وارد کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setHelpText');
    }
    public function setHelpText(){
        setConfig('help',$this->text);
        setState($this->chat_id,'adminMenu');
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"متن ثبت شد !",
            'reply_markup'=>setTextButton()
        ]);
    }
    public function setRole(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"متن  را وارد کنید",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setRoleText');
    }
    public function setRoleText(){
        setConfig('role',$this->text);
        setState($this->chat_id,'adminMenu');
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"متن ثبت شد !",
            'reply_markup'=>setTextButton()
        ]);
    }
}
