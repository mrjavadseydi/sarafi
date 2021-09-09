<?php

namespace App\Http\Controllers;

trait Price
{
    public function initSetPrice(){
        $price = number_format(getConfig('buyPrice'));
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"\n قیمت خرید فعلی  :$price مبلغ خرید را وارد کنید (اعداد لاتین):",
            'reply_markup'=>backButton()
        ]);
        setState($this->chat_id,'setBuyPrice');
    }
    public function setBuyPrice(){
        if (is_numeric($this->text)){
            $price = number_format(getConfig('sellPrice'));
            setConfig('buyPrice',$this->text);
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"\n قیمت فروش فعلی :$price مبلغ فروش را وارد کنید (اعداد لاتین):",
                'reply_markup'=>backButton()
            ]);
            setState($this->chat_id,'setSellPrice');
        }else{
            $price = number_format(getConfig('buyPrice'));
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"\n قیمت خرید فعلی :$price مبلغ خرید را وارد کنید (اعداد لاتین):",
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function setSellPrice(){
        if (is_numeric($this->text)){
            setConfig('sellPrice',$this->text);
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"ثبت شد ",
                'reply_markup'=>adminButton()
            ]);
            setState($this->chat_id,'adminMenu');
        }else{
            $price = number_format(getConfig('sellPrice'));
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"\n قیمت فروش فعلی :$price مبلغ فروش را وارد کنید (اعداد لاتین):",
                'reply_markup'=>backButton()
            ]);
        }

    }
}
