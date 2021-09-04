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
                ['تنظیم متن','تنظیم گروه'],
                ['تنظیم قیمت','تنظیم کانال'],
                ['موجودی حساب'],
                ['ارسال همگانی'],
                ['بازگشت ↪️']
              ]
        );
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}
if(!function_exists('setTextButton')){
    function setTextButton()
    {
        $btn = Keyboard::button(
            [
                ['تماس با ما'],
                ['سوالات متداول'],
                ['راهنما'],
                ['قوانین'],
                ['عضویت در کانال'],
                ['شماره کارت'],
                ['بانک کارت'],
                ['نام صاحب کارت'],
                ['بازگشت ↪️']
            ]
        );
        return Keyboard::make(['keyboard' => $btn, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }
}
if(!function_exists('setGroupButton')){
    function setGroupButton()
    {
        $btn = Keyboard::button(
            [
                ['گروه خرید'],
                ['گروه فروش'],
                ['گروه احراز هویت'],
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
    function activateUser($id,$chat_id)
    {
        return keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => "تایید",
                        'callback_data' => "activate-$id-$chat_id"
                    ],
                    [
                        'text' => "رد",
                        'callback_data' => "deactive-$id-$chat_id"
                    ],
                    [
                        'text' => "بلاک",
                        'callback_data' => "block-$id-$chat_id"
                    ],

                ]
            ],
        ]);
    }
}
if(!function_exists('payUrlButton')){
    function payUrlButton($id)
    {
        return keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => "پرداخت",
                        'url' =>route('gateway.init',$id)
                    ]

                ]
            ],
        ]);
    }
}
if(!function_exists('acceptPay')){
    function acceptPay($chat_id,$id)
    {
        return keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => "تایید",
                        'callback_data' => "pay-$id-$chat_id"
                    ],
                    [
                        'text' => "رد",
                        'callback_data' => "notPay-$id-$chat_id"
                    ],
                    [
                        'text' => "بلاک",
                        'callback_data' => "block-$id-$chat_id"
                    ],

                ]
            ],
        ]);
    }
}
if(!function_exists('paidButton')){
    function paidButton($chat_id,$id)
    {
        return keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => "واریز شد!",
                        'callback_data' => "paid-$id-$chat_id"
                    ]

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
