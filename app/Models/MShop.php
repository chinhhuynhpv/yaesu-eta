<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MShop extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Get the user associated with the request.
     */
    public function owner()
    {
        return $this->hasOne(MShopUser::class, 'shop_id');
    }
}
