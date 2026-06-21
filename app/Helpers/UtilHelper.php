<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;


class UtilHelper
{

    public static function userSessionId() {
        return auth()->user()->usu_id;
    }

    // Ejemplo de session
    public static function sessionNameUser(){

        Redis::set('nombre', 'Gonzalo el usuario');

        return Redis::get('nombre');
    }

    // Ejemplo de cache
    public static function cacheUser(){
        Cache::put('usuario', 'Gonzalo', 3600);
        return Cache::get('usuario');
    }


}
