<?php

namespace App\Enums;

enum InquireType: int
{
    case VACATION = 1;
    case DAY_OFF = 2;
    case REMOTE = 3;
    case MEDICAL = 4;
    case UNPAID = 5;
}
