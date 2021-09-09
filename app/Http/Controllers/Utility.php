<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\Sell;


trait Utility
{

    public function getPrice(){
        $buy = number_format(getConfig('buyPrice'));
        $sell = number_format(getConfig('sellPrice'));
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"قیمت خرید : $buy \n قیمت فروش : $sell",
            'reply_markup'=>backButton()
        ]);
    }
    public function getRole(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>getConfig('role'),
            'reply_markup'=>backButton()
        ]);
    }
    public function getHelp(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>getConfig('help'),
            'reply_markup'=>backButton()
        ]);
    }
    public function profile(){
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
            $name = $this->user->name;
            $card = $this->user->card;
            $shaba = $this->user->shaba;
            $phone = $this->user->phone;
            $text = "❇️نام  :  $name
✅شماره کارت  : $card
✳️شماره شبا : $shaba
❎تلفن تماس : $phone
🔰احراز هویت شما انجام شده 🔰
";
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>$text,
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function getFAQ(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>getConfig('faq'),
            'reply_markup'=>backButton()
        ]);
    }
    public function getContactUs(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>getConfig('contactUs'),
            'reply_markup'=>backButton()
        ]);
    }
    public function userTransaction(){
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
            $buy = Buy::where('chat_id',$this->chat_id)->orderBy('id','desc')->get();
            $sell =  Sell::where('chat_id',$this->chat_id)->orderBy('id','desc')->get();
            if (count(buy)==0 && count($sell)==0){
                sendMessage([
                    'chat_id'=>$this->chat_id,
                    'text'=>"❕هیچ خرید و فروشی ندارید ",
                    'reply_markup'=>backButton()
                ]);
            }
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"لطفا صبر کنید !",
                'reply_markup'=>backButton()
            ]);
            $text = "";
            foreach ($buy as $i=> $item) {
                if($i==0){
                    $text.="لیست فروش های شما به ما\n";
                }
                $status = $item->status;
                if($status==-2){
                    $status = "❌ ارسال ووچر انجام نشده";
                }elseif ($status==-1){
                    $status = "❌کد ووچر اشتباه میباشد";
                }elseif($status==1){
                    $status="✅ووچر ثبت شده";
                    if ($item->paid){
                        $status.="\n 🔰 پرداخت انجام شده";
                    }else{
                        $status.="\n 🔰 در انتظار واریز توسط مدیریت ";
                    }
                }
                $text .= $status."\n";
                $voocher = $item->voocher;
                $activator = $item->activator;
                $amount = $item->amount ;
                $price = $item->price;
                $text .="💠 کد ووچر : $voocher \n 🌀 فعال ساز : $activator \n ⚜️ تعداد : $amount \$ \n 🏧 مبلغ : $price 〰️➰〰️➰〰️";
            }
            $text = "";

            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>$text,
                'reply_markup'=>backButton()
            ]);
            foreach ($sell as $i=> $item){
                if($i==0){
                    $text.="لیست خرید های شما از ما \n";
                }
                $status = $item->status;
                if($status==0){
                    $status = "❌ رسید ارسال نشده";
                }elseif($status==-1){
                    $status = "❌ توسط مدیریت لغو شد";
                }elseif($status==1){
                    $status = "✅تایید شده";
                }
                $text.=$status."\n";
                $amount = $item->amount;
                $price = $item->price;
                $voocher = $item->voocher;
                $activator = $item->activator;
                $text .="💠 کد ووچر : $voocher \n 🌀 فعال ساز : $activator \n ⚜️ تعداد : $amount \$ \n 🏧 مبلغ : $price 〰️➰〰️➰〰️";
            }
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>$text,
                'reply_markup'=>backButton()
            ]);
        }
    }
}
