<?php

use Telegram\Bot\Keyboard\Keyboard;

if(!function_exists('backButton')){
    function backButton()
    {
        $btn = Keyboard::button([['بازگشت ↪️']]);
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}

if(!function_exists('menuButton')){
    function menuButton()
    {
        $btn = Keyboard::button(
           [
                ['💰 فروش به ما', '💳 خرید از ما'],
                ['💸 خرید/فروش های من', '📄 نرخ ارزها'],
                ['⁉️ سوالات متداول', '💡 راهنما'],
                ['📞 تماس با ما', '👮 قوانین و مقررات'],
                ['👤 پروفایل کاربری']
           ]
        );
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}
if(!function_exists('adminButton')){
    function adminButton()
    {
        $btn = Keyboard::button(
              [
                ['ارسال پیام به شخص خاص'],
                ['تنظیم قیمت خرید و فروش'],
                ['تنظیم متن دکمه تماس با ما'],
                ['تنظیم آیدی پشتیبانی'],
                ['موجودی حساب'],
                ['ارسال همگانی'],
                ['بازگشت ↪️']
              ]
        );
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}
if(!function_exists('adminButton')){
    function adminButton()
    {
        $btn = Keyboard::button(
       [
           ['فروش های من', 'خرید های من'],
           ['بازگشت ↪️']
       ]
        );
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}
if(!function_exists('activationButton')){
    function activationButton()
    {
        $btn = Keyboard::button(
            [
                ['🟢 احراز هویت 🟢'],
                ['بازگشت ↪️']
            ]

        );
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}
if(!function_exists('joinKey')){
    function joinKey()
    {
        return keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => "عضویت در کانال",
                        'url' => "https://t.me/".getConfig('channel_id')
                    ]
                ]
            ],
        ]);
    }
}
if(!function_exists('sendPhone')){
    function sendPhoneButton()
    {
        $btn = Keyboard::button(
            [
                [
                    ['text' => "📞 ارسال شماره تلفن ☎️", 'request_contact' => true]]
                ,
                [
                    ['text'=>'بازگشت ↪️']
                ]
            ]

        );
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}
if(!function_exists('activateUser')){
    function activateUser($id)
    {
        return keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => "تایید",
                        'callback_data' => "activate-$id"
                    ],
                    [
                        'text' => "رد",
                        'callback_data' => "deactive-$id"
                    ],
                    [
                        'text' => "بلاک",
                        'callback_data' => "block-$id"
                    ],

                ]
            ],
        ]);
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