<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function __construct()
    {
    }

    public $message_type;
    public $text;
    public $chat_id;
    public $from_id;
    public $user = null;
    use Activation , AdminSetting , CallBackQuery,CallBuy,CallSell , Channel,Group,Price,Text,Utility;
    public function index(Request $request)
    {
        $req = $request->toArray();
        devLog($req);
        if(\Cache::has($req['update_id'])){
            die();
        }else{
            \Cache::put($req['update_id'],60,60);
        }
//        die();
        $this->message_type = messageType($req);
        if ($this->message_type == "callback_query") {
            $this->initCallBack($req);
            exit();
        }
        $this->text = $req['message']['text'] ?? "//**";
        $this->chat_id = $req['message']['chat']['id'] ?? "";
        $this->from_id =$req['message']['from']['id'] ?? "";
        if($req['message']['chat']['type']=="group"){
            if (!($user = Member::where('chat_id', $this->from_id)->first()) ) {
                $user = Member::create([
                    'chat_id' => $this->from_id
                ]);
            }else{
                $user = Member::where('chat_id', $this->from_id)->first();
            }
            $this->user = $user;
        }elseif($req['message']['chat']['type']=="private"){
            if (!($user = Member::where('chat_id', $this->chat_id)->first()) ) {
                $user = Member::create([
                    'chat_id' => $this->chat_id
                ]);
            }else{
                $user = Member::where('chat_id', $this->chat_id)->first();
            }
            $this->user = $user;
        }else{
            exit();
        }

        if($this->text =="/id"){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"chat id : ".$this->chat_id. "\n from id : ".$this->from_id
            ]);
            exit();
        }
        if ($this->text == "/start" || $this->text == 'بازگشت ↪️') {
            $this->start();
            exit();
        }

        if ($this->user->admin) {
            switch ($this->user->state) {

            }
            switch ($this->text) {
                case "/panel":
                    $this->iniAdmin();
                    break;

            }
        }
        if (getConfig('channel') != false) {
            if (!joinCheck($this->chat_id, getConfig('channel'))) {
                sendMessage([
                    'chat_id' => $this->chat_id,
                    'text' => getConfig('join_text'),
                    'reply_markup' => joinKey()
                ]);
                return 0;
            }
        }


        switch ($this->user->state) {
            case "SendPhone":
                $this->receivePhone($req);
                break;
            case "SendNationalCode":
                $this->receiveNationalCode();
                break;
            case "SendShaba":
                $this->receiveShaba();
                break;
            case "SendCardNumber":
                $this->receiveCard();
                break;
            case "SendCardPhoto":
                $this->receiveCardPhoto($req);
                break;
            case "SendBankCardPhoto":
                $this->receiveBankCardPhoto($req);
                break;
            case "SendVerificationVideo":
                $this->receiveVerificationVideo($req);
                break;
            case  "GiveSellCount":
                $this->receiveSellCount();
                break;
            case  "GiveResid":
                $this->receiveResid($req);
                break;
            case  "GiveVoocherKey":
                $this->receiveVoocherKey();
                break;
            case  "getActivator":
                $this->receiveActivator();
                break;
            default:
                break;
        }
        if ($this->message_type == "text") {
            switch ($this->text) {
                case 'بازگشت ↪️' :
                case "/start":
                    $this->start();
                    break;
                case "💳 خرید از ما":
                    $this->initSell($req);
                    break;
                case "💰 فروش به ما":
                    $this->initBuy();
                    break;
                case "🟢 احراز هویت 🟢" :
                    $this->initActivate($req);
                    break;
                case "📄 نرخ ارزها":
                    $this->getPrice();
                    break;
                case "⁉️ سوالات متداول":
                    $this->getFAQ();
                    break;
                case "📞 تماس با ما":
                    $this->getContactUs();
                    break;
                case "💡 راهنما":
                    $this->getHelp();
                    break;
                case "👮 قوانین و مقررات":
                    $this->getRole();
                    break;
                case  "👤 پروفایل کاربری":
                    $this->getProfile();
                    break;
                case "💸 خرید/فروش های من":
                    $this->userTransaction();
                    break;

                default :
                    break;

            }

        }
    }

    public function start()
    {
        nullState($this->chat_id);
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => "به منو اصلی خوش آمدید",
            'reply_markup' => menuButton()
        ]);
    }

}
