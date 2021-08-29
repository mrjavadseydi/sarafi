<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class  CallBackQueryController extends SellController
{
    public function initCallBack($req){
        $data = $req["callback_query"]['data'];
        $chat_id = $req["callback_query"]['message']['chat']['id'];
        $message_id = $req["callback_query"]["message"]['message_id'];
        $text = $req["callback_query"]['message']['text'];
        $ex = explode('-',$data);
        switch ($ex[0]){
            case 'activate':
                $this->confirmValidation($ex[1]);
                editMessageText([
                    'chat_id'=>$chat_id,
                    'text'=>$text . "\n در " .now()."تایید شد"
                ]);
                break;
            case "":
                $this->denyValidation($ex[1]);
                editMessageText([
                    'chat_id'=>$chat_id,
                    'text'=>$text . "\n در " .now()."رد  شد"
                ]);
                break;
            case "block":
                $this->blockUser($ex[1]);
                editMessageText([
                    'chat_id'=>$chat_id,
                    'text'=>$text . "\n در " .now()."بلاک شد"
                ]);
                break;
        }
    }


    public function confirmValidation($id){
        Member::where('id',$id)->update([
            'active'=>2
        ]);
        sendMessage([
            'chat_id'=>Member::whereId($id)->first()->chat_id,
            'text'=>'مدارک شما با موفقیت تایید شد',
            'reply_markup'=>backButton()
        ]);
    }
    public function denyValidation($id){
        Member::where('id',$id)->update([
            'active'=>0
        ]);
        sendMessage([
            'chat_id'=>Member::whereId($id)->first()->chat_id,
            'text'=>'احراز هویت شما رد شد لطفا نسبت به ارسال  محدد مدارک اقدام نمایید',
            'reply_markup'=>backButton()
        ]);
    }
    public function blockUser($id){
        Member::where('id',$id)->update([
            'block'=>true
        ]);
    }
}
