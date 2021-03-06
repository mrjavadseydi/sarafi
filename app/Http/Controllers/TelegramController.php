<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TelegramController extends Controller
{

    public $message_type;
    public $text;
    public $chat_id;
    public $from_id;
    public $user = null;
    use Activation, AdminSetting, CallBackQuery, CallBuy, CallSell, Channel, Group, Price, Text, Utility,SendAll;

    public function index(Request $request)
    {
        $req = $request->toArray();
//        devLog('asdfasdf');
        if (Cache::has($req['update_id'])) {
            die();
        } else {
            Cache::put($req['update_id'], 60, 60);
        }
        $this->message_type = messageType($req);
        if ($this->message_type == "callback_query") {
            $this->initCallBack($req);
            exit();
        }
        $this->text = $req['message']['text'] ?? "//**";
        $this->chat_id = $req['message']['chat']['id'] ?? "";
        $this->from_id = $req['message']['from']['id'] ?? "";
        if ($req['message']['chat']['type'] == "group") {
            if (!($user = Member::where('chat_id', $this->from_id)->first())) {
                $user = Member::create([
                    'chat_id' => $this->from_id
                ]);
            } else {
                $user = Member::where('chat_id', $this->from_id)->first();
            }
            $this->user = $user;
        } elseif ($req['message']['chat']['type'] == "private") {
            if (!($user = Member::where('chat_id', $this->chat_id)->first())) {
                $user = Member::create([
                    'chat_id' => $this->chat_id
                ]);
            } else {
                $user = Member::where('chat_id', $this->chat_id)->first();
            }
            $this->user = $user;
        } else {
            exit();
        }
        if ($this->text == "/start" || $this->text == 'بازگشت ↪️') {
            $this->start();
            exit();
        }
        if ($this->user->admin) {
            switch ($this->user->state) {
                case "setBuyPrice":
                    $this->setBuyPrice();
                    break;
                case "setSellPrice":
                    $this->setSellPrice();
                    break;
                case "setChannel":
                    $this->setChannel();
                    break;
                case "setResidGroupId":
                    $this->setResidGroupId();
                    break;
                case "setValidationGroupId":
                    $this->setValidationGroupId();
                    break;
                case "setPayOutGroupId":
                    $this->setPayOutGroupId();
                    break;
                case "setHelpText":
                    $this->setHelpText();
                    break;
                case "setContactUsText":
                    $this->setContactUsText();
                    break;
                case "setFaqText":
                    $this->setFaqText();
                    break;
                case "setRoleText":
                    $this->setRoleText();
                    break;
                case "sendAllReceive":
                    $this->sendAllReceive();
                    break;
                case "sendAllConfirm":
                    $this->sendAllConfirm();
                    break;
            }
            switch ($this->text) {
                case "/panel":
                    $this->initAdmin();
                    break;
                case "تنظیم قیمت":
                    $this->initSetPrice();
                    break;
                case "تنظیم گروه":
                    $this->setGroupMenu();
                    break;
                case "تنظیم متن":
                    $this->setTextMenu();
                    break;
                case "تنظیم کانال":
                    $this->initChannel();
                    break;
                case "گروه خرید":
                    $this->setResidGroup();
                    break;
                case "گروه فروش":
                    $this->setPayOutGroup();
                    break;
                case "گروه احراز هویت":
                    $this->setValidationGroup();
                    break;
                case "تماس با ما":
                    $this->setContactUs();
                    break;
                case "سوالات متداول":
                    $this->setFaq();
                    break;
                case "راهنما":
                    $this->setHelp();
                    break;
                case "قوانین":
                    $this->setRole();
                    break;
                case "ارسال همگانی":
                    $this->sendAllInit();

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
            case "SendName":
                $this->receiveName();
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

        if ($this->message_type == "message") {
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
                    $this->profile();
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
