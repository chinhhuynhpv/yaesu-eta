<?php

namespace App\Models;

class MOptionPlan extends MPlan
{
    public function getOptionTypeAttribute($value) {
        switch ($value) {
            case '1':
                return __('Function');
            case '2':
                return __('Discount');
            default:
                return null;
        }
    }


    static public function columnConstraints()
    {
        $contraints = parent::columnConstraints();

        return array_merge(
            [
                'option_type' => 'string|in:1,2',
            ],
            $contraints,
            [
                'discount_target1' => 'string|nullable|max:10',
                'discount_target2' => 'string|nullable|max:10',
                'discount_target3' => 'string|nullable|max:10',
                'discount_target4' => 'string|nullable|max:10',
                'discount_target5' => 'string|nullable|max:10'
            ]
        );
    }

    public function fieldNames()
    {
        $names = parent::fieldNames();

        return array_merge(
            [
                'option_type' => 'Option type'
            ],
            $names,
            [
                'discount_target1' => 'Discount target 1',
                'discount_target2' => 'Discount target 2',
                'discount_target3' => 'Discount target 3',
                'discount_target4' => 'Discount target 4',
                'discount_target5' => 'Discount target 5',
            ]
        );
    }
}
