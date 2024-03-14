<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MTax extends Model
{
    use HasFactory;

    //The table of model
    protected $table = 'm_taxs';

    /**
     * Get the effective tax
     * @param $date
     * @return MTax | null
     */
    public static function getEffectiveTax($date) {
        return static::whereDate('start_date', '<=', $date)
            ->where(function($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $date);
            })
            ->orderBy('id', 'DESC')
            ->first();
    }
}
