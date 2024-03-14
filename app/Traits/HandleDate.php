<?php

namespace App\Traits;

use \Carbon\Carbon;

trait HandleDate
{
    public function getUpdatedAtAttribute($value)
    {
        return $this->parseDate($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->parseDate($value);
    }

    private function parseDate($value)
    {
        if (!$value) return $value;

        return Carbon::parse($value)->format('Y/m/d');
    }
}
