<?php

namespace App\Enums;

enum HabitRecurrence: string
{
    case DIARIO = 'DIARIO';
    case SEMANAL = 'SEMANAL';
    case MENSUAL = 'MENSUAL';
    case PERSONALIZADO = 'PERSONALIZADO';
}
