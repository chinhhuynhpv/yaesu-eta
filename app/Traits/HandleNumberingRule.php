<?php

namespace App\Traits;

use \Carbon\Carbon;

trait HandleNumberingRule
{
    static public function yearRule()
    {
        $now = Carbon::now();
        $difference =  $now->year - 2019;

        return chr(ord("A") + $difference);
    }
}
