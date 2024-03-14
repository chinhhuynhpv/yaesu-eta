<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSaleDetail extends Model
{
    use HasFactory;

    protected $table = 'w_sale_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['shop_id', 'billing_id', 'contract_date', 'plan_type', 'plan_id', 'plan_num',
    //                        'plan_name', 'unit_price', 'incentive_unit_price', 'amount', 'total_price', 'incentive_total_price'];
    protected $guarded = ['id'];
}
