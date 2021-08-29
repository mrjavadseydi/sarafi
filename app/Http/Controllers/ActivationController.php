<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class  ActivationController extends TelegramController
{
    public function initActivate($req){
        if ($this->user->activate==2){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"شما قبلا احراز هویت انجام دادید!",
                'reply_markuo'=>backButton()
            ]);

        }elseif ($this->user->activate==1){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"احراز هویت شما رد شد!",
                'reply_markuo'=>backButton()
            ]);
        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'لطفا از طریق دکمه زیر شماره تلفن خود را ارسال کنید',
                'reply_markup'=>sendPhoneButton()
            ]);
            setState($this->chat_id,'SendPhone');
        }
    }
    public function receivePhone($req){
        if (!isset($req["message"]["contact"]["phone_number"])||$req["message"]["contact"]["user_id"]!=$this->chat_id){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'لطفا از طریق دکمه زیر شماره تلفن خود را ارسال کنید',
                'reply_markup'=>sendPhoneButton()
            ]);
        }elseif(strpos($req["message"]["contact"]["phone_number"],'989')){
            Member::where('chat_id',$this->chat_id)->update([
                'phone'=>$req['message']['contact']['phone_number']
            ]);
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'لطفا کد ملی خود را با اعداد لاتین وارد کنید',
                'reply_markup'=>backButton()
            ]);
            setState($this->chat_id,'SendNationalCode');
        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'شماره تلفن شما صحیح نمی باشد',
                'reply_markup'=>sendPhoneButton()
            ]);

        }
    }
    public function receiveNationalCode(){
        if(is_numeric($this->text)&&strlen($this->text)==10 ){
            Member::where('chat_id',$this->chat_id)->update([
                'national_id'=>$this->text
            ]);
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'لطفا یک عکس از کارت ملی خود ارسال کنید',
                'reply_markup'=>backButton()
            ]);
            setState($this->chat_id,'SendCardPhoto');
        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'کد ملی شما صحیح نمی باشد',
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function receiveCardPhoto($req){
        if ($this->message_type=="photo"){
            $photo = end($req["message"]['photo'])['file_id'];
            Member::where('chat_id',$this->chat_id)->update([
                'national_card'=>$photo
            ]);
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'لطفا یک عکس از کارت بانکی خود ارسال کنید',
                'reply_markup'=>backButton()
            ]);
            setState($this->chat_id,'SendBankCardPhoto');

        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'لطفا یک عکس از کارت ملی خود ارسال کنید',
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function receiveBankCardPhoto($req){
        if ($this->message_type=="photo"){
            $photo = end($req["message"]['photo'])['file_id'];
            Member::where('chat_id',$this->chat_id)->update([
                'bank_card'=>$photo
            ]);
            $text = " لطفا یک فیلم کوتاه سلفی از خود گرفته و کارت ملی و کارت عابر خود را در دست گرفته و جمله *به نام خدا این جانب (نام و نام خانوادگی شما) در تاریخ (تاریخ امروز) این فیلم جهت احراز هویت در ربات تلگرامی صرافی   می باشد.* \nتوجه داشته باشید که در طول ضبط فیلم کارت ملی و کارت عابر بانک خود را در دست داشته باشید .";
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>$text,
                'reply_markup'=>backButton()
            ]);
            setState($this->chat_id,'SendVerificationVideo');
        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'لطفا یک عکس از کارت بانکی خود ارسال کنید',
                'reply_markup'=>backButton()
            ]);
        }
    }
    public function receiveVerificationVideo($req){
        if ($this->message_type=="video") {
            $video = $req['message']['video']['file_id'];
            Member::where('chat_id',$this->chat_id)->update([
                'video'=>$video,
                'active'=>1
            ]);
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>'مدارک  شما جهت بررسی به مدیریت ارسال شد !',
                'reply_markup'=>backButton()
            ]);
            $text = "[$this->chat_id](tg://user?id=$this->chat_id) \n
            $this->user->name \n
            $this->user->national_id \n
            $this->user->phone
            ";
            try {
                Telegram::sendMediaGroup([
                    'chat_id'=>getConfig('validationGroup'),

                    'media'=>[
                        [
                            'type'=>'photo',
                            'media'=>$this->user->national_card,
                            'parse_mode' => "Markdown",'caption'=>"$text"
                        ],
                        [
                            'type'=>'photo',
                            'media'=>$this->user->bank_card
                        ],
                        [
                            'type'=>'video',
                            'media'=>$video
                        ]
                    ]
                ]);
                sendMessage([
                    'chat_id'=>getConfig('validationGroup'),
                    'text'=>"ایا مدارد بالا را تایید میکنید ؟",
                    'reply_markup'=>activateUser($this->user->id),
                ]);
            }catch (Exception $e){

            }
            $this->start();
        }else{
            $text = " لطفا یک فیلم کوتاه سلفی از خود گرفته و کارت ملی و کارت عابر خود را در دست گرفته و جمله *به نام خدا این جانب (نام و نام خانوادگی شما) در تاریخ (تاریخ امروز) این فیلم جهت احراز هویت در ربات تلگرامی صرافی   می باشد.* \nتوجه داشته باشید که در طول ضبط فیلم کارت ملی و کارت عابر بانک خود را در دست داشته باشید .";
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>$text,
                'reply_markup'=>backButton()
            ]);
        }
    }
}
