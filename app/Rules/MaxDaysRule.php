<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class MaxDaysRule implements Rule
{
    protected $maxDays;

    public function __construct($maxDays)
    {
        $this->maxDays = $maxDays;
    }

    public function passes($attribute, $value)
    {
        $start = Carbon::parse(request()->input('start'));
        $end = Carbon::parse($value);
        $daysDifference = $end->diffInDays($start);

        return $daysDifference <= $this->maxDays;
    }

    public function message()
    {
        return "The number of days between start and end cannot exceed {$this->maxDays} days.";
    }

}
