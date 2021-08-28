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

if(!function_exists('back‌Button')){
    function back‌Button()
    {
        $btn = Keyboard::button([['برگشت 🔙']]);
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}

if(!function_exists('WalletKey')){
    function WalletKey()
    {
        return Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => "💵 موجودی حساب 💵",
                        'callback_data' => "mojodi"
                    ]
                ],

                [
                        [
                        'text' => '🎫 برداشت ووچر 🎫',
                        'callback_data' => "bardashtv"

                    ]
                ],
                [
                        [
                        'text' => 'تراکنش ها',
                        'callback_data' => "pdf"

                    ]
                ]

            ],
        ]);
    }
}
