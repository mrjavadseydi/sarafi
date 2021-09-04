<?php


namespace App\Http\Controllers;


use App\Jobs\SendToTelegramJob;
use App\Models\Member;
use Illuminate\Support\Facades\Cache;

trait SendAll
{

    public function sendAllInit(){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"متن  را وارد کنید",
                'reply_markup'=>backButton()
            ]);
            setState($this->chat_id,'sendAllReceive');
    }
    public function sendAllReceive(){
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$this->text."\n ایا تایید میکنید متن برای کاربران ارسال شود ",
            'reply_markup'=>ConfirmAndBackButton()
        ]);
        Cache::put('sendAll',$this->text);

        setState($this->chat_id,'sendAllConfirm');
    }
    public function sendAllConfirm(){
        if ($this->text=="✅تایید"){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"در صف ارسال قرار گرفت",
                'reply_markup'=>adminButton()
            ]);
            $text = Cache::pull('sendAll');
            foreach (Member::all() as $member){

                SendToTelegramJob::dispatch($member->chat_id,$text);
            }
            setState($this->chat_id,'adminMenu');
        }else{
            $this->initAdmin();
        }


    }
}
