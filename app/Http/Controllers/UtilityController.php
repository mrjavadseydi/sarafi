<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use Illuminate\Http\Request;

class UtilityController extends BuyController
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
}
