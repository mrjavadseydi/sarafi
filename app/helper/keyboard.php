<?php

use Telegram\Bot\Keyboard\Keyboard;

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
        return keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => "💵 موجودی حساب 💵",
                        'callback_data' => "mojodi"
                    ]
                ]
            ],
        ]);
    }
}
