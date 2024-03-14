<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HandleSearch;
use App\Traits\HandleInput;

class MPlan extends Model
{
    use HandleInput;
    use HandleSearch;
    use HasFactory;
    use SoftDeletes;

    // Optional Search
    protected $search = ['plan_num', 'plan_name'];

    public function getCalculationUnitAttribute($value) {
        switch ($value) {
            case '1':
                return __('Line');
            case '2':
                return __('Application');
            default:
                return null;
        }
    }

    public function getShopWebAttribute($value) {
        switch ($value) {
            case '1':
                return __('Enabled');
            case '2':
                return __('Hidden');
            default:
                return null;
        }
    }

    public function getAuthorityAttribute($value) {
        switch ($value) {
            case '1':
                return __('Dealer');
            case '2':
                return __('Operator');
            case '3':
                return __('Administrator');
            default:
                return null;
        }
    }

    public function getCalculationTypeAttribute($value) {
        switch ($value) {
            case '1':
                return __('Occurrence month only');
            case '2':
                return __('Monthly');
            default:
                return null;
        }
    }

    public function getUsageDetailsDescriptionAttribute($value) {
        switch ($value) {
            case 1:
                return __('yes');
            case 0:
                return __('no');
            default:
                return null;
        }
    }

    public function getIncentiveDescriptionAttribute($value) {
        switch ($value) {
            case 1:
                return __('yes');
            case 0:
                return __('no');
            default:
                return null;
        }
    }

    public static function getEffectivePlan($date) {
        return static::whereDate('effective_date', '<=', $date)
            ->whereDate('expire_date', '>=', $date)
            ->orderBy('id', 'DESC')
            ->first();
    }

    static public function generatePlanNum()
    {
        $lastOption = self::orderBy('id', 'DESC')->first();
        return sprintf("%06d", (int) $lastOption->id + 1);
    }

    static public function columnConstraints()
    {
        return [
            'calculation_unit' => 'string|in:1,2',
            'plan_name' => 'string|nullable|max:255',
            'effective_date' => 'date|nullable|regex:/^\d{4,4}-\d{2,2}-\d{2,2}$/',
            'expire_date' => 'date|nullable|regex:/^\d{4,4}-\d{2,2}-\d{2,2}$/',
            'shop_web' => 'string|in:1,2',
            'authority' => 'string|in:1,2,3',
            'calculation_type' => 'string|in:1,2',
            'usage_details_description' => 'boolean',
            'incentive_description' => 'boolean',
            'cancellation_limit_period' => 'string|nullable|max:4',
            'usage_unit_price' => self::priceConstraints(),
            'period' => 'string|nullable|max:4',
            'incentive_unit_price' => self::priceConstraints(),
            'incentive_unit_price2' => self::priceConstraints(),
            'incentive_unit_price3' => self::priceConstraints(),
            'midway_cancell_unit_price' => self::priceConstraints(),
        ];
    }

    public function fieldNames()
    {
        return [
            'plan_num' => 'Plan No',
            'calculation_unit' => 'Calculation unit',
            'plan_name' => 'Plan name',
            'effective_date' => 'Effective date',
            'expire_date' => 'Expire date',
            'shop_web' => 'Dealer Web',
            'authority' => 'Authority',
            'calculation_type' => 'Calculation type',
            'usage_details_description' => 'Usage statement',
            'incentive_description' => 'Incentive details',
            'cancellation_limit_period' => 'Cancellation limit period',
            'usage_unit_price' => 'Usage fee unit price',
            'period' => 'Period',
            'incentive_unit_price' => 'Incentive unit price',
            'incentive_unit_price2' => 'Incentive unit price 2',
            'incentive_unit_price3' => 'Incentive unit price 3',
            'midway_cancell_unit_price' => 'Midway Cancel unit price',
        ];
    }

    static public function priceConstraints()
    {
        return 'string|nullable|max:13|regex:/^[0-9]+$/';
    }
}
