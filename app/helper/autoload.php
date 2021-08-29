<?php

use App\Models\Config;
use Illuminate\Filesystem\Cache;

require_once(__DIR__."/telegram.php");


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
