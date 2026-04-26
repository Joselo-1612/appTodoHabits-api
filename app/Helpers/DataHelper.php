<?php

namespace App\Helpers;


class DataHelper
{

    public static function getDayNumber(string $dayString) {
        switch ($dayString) {
            case 'lunes':
                return 1;
            case 'martes':
                return 2;
            case 'miercoles':
                return 3;
            case 'jueves':
                return 4;
            case 'viernes':
                return 5;
            case 'sabado':
                return 6;
            case 'domingo':
                return 7;
        }
    }


}
