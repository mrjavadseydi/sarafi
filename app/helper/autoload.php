<?php

use App\Models\Config;
use \Illuminate\Support\Facades\Cache ;
require_once(__DIR__ . "/telegram.php");
require_once(__DIR__ . "/keyboard.php");
require_once(__DIR__ . "/pay.php");


function getConfig($name)
{
    if (Cache::has('config' . $name)) {
        return Cache::get('config' . $name);
    } else {

        if ($value = Config::where('name', $name)->first()) {
            $value = Config::where('name', $name)->first()->value;
            Cache::put('config' . $name, $value, 360);
            return $value;
        }
        return false;
    }
}

function setConfig($name, $value)
{
    if ($conf = Config::where('name', $name)->first()) {
        $conf->update([
            'value' => $value
        ]);
    } else {
        Config::create([
            'name' => $name,
            'value' => $value
        ]);
    }
}

function setState($chat_id, $state)
{
    \App\Models\Member::where('chat_id', $chat_id)->update([
        'state' => $state
    ]);
}

function nullState($chat_id)
{
    \App\Models\Member::where('chat_id', $chat_id)->update([
        'state' => Null
    ]);
}

function getState($chat_id)
{
    return \App\Models\Member::where('chat_id', $chat_id)->first()->state;
}
