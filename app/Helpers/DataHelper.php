<?php

namespace App\Helpers;

enum StatusModel: int {
    case ACTIVE = 1;
    case INACTIVE = 0;
}

enum StatusHabitComplete: int
{
    case DONE = 1;
    case SKIPPED = 0;
}

