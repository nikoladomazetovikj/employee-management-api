<?php

namespace App\Enums;

enum Status: int
{
    case PENDING = 1;
    case DECLINED = 2;
    case APPROVED = 3;
}
