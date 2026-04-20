<?php

namespace App\Enums;

enum HabitEnum: string
{
    case ACTIVE = '1';
    case INACTIVE = '0';
    case RECURRENCE_DIARIO = 'diaria';
    case RECURRENCE_SEMANAL = 'semanal';
    case RECURRENCE_MENSUAL = 'mensual';
    case RECURRENCE_PERSONALIZADO = 'personalizado';
}
