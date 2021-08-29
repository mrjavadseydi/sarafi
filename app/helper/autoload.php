<?php

use App\Models\Config;
use Illuminate\Filesystem\Cache;

require_once(__DIR__."/telegram.php");
require_once(__DIR__."/keyboard.php");


function getConfig($name)
{
    if(Cache::has('config'.$name)){
        return Cache::get('config'.$name);
    }else{
        $value  = Config::where('name',$name)->first()->value;
        Cache::put('config'.$name, $value, 360);
        return $value;
    }
}
function setConfig($name,$value){
    $value  = Config::where('name',$name)->update([
        'value'=> $value
    ]);
    Cache::put('config'.$name, $value, 360);
    return $value;
}
function setState($chat_id,$state)
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
    return \App\Models\Member::where('chat_id',$chat_id)->first()->state;
}
