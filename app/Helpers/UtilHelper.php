<?php

namespace App\Helpers;


class UtilHelper
{

    public static function userSessionId() {
        return auth()->user()->usu_id;
    }


}
