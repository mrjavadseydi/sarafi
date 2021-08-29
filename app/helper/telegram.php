<?php

use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

if(!function_exists('sendMessage')){
    function sendMessage($arr)
    {
        try
        {
            return Telegram::sendMessage($arr);
        }
        catch(TelegramResponseException $e)
        {
            return "user has been blocked!";
        }
    }
}
