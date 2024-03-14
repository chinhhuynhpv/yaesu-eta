<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TUserCommission extends Model
{
    use HasFactory;

    /**
     * Get the user associated with the request.
     */
    public function line()
    {
        return $this->belongsTo(MUserLine::class, 'line_id')->withDefault();
    }


    /**
     * Get the user associated with the request.
     */
    public function commission()
    {
        return $this->belongsTo(MCommission::class, 'commission_id')->withDefault();
    }
}
