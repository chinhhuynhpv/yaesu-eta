<?php

namespace App\Models;

use App\Traits\HandleDate;
use App\Traits\HandleInput;
use App\Traits\HandleSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class MSim extends Model
{
    use HandleDate;
    use HandleInput;
    use HandleSearch;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'm_sims';

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case '1':
                return __('new');
            case '2':
                return __('in_use');
            case '3':
                return __('in_pause');
            case '4':
                return __('abolition');
            case '5':
                return __('in_reissue');
            default:
                return null;
        }
    }

    public function getSimOpeningDateAttribute($value) {
        return $this->parseDate($value);
    }

    public static function columnConstraints()
    {
        return [
            'sim_num' =>[
                'required',
                'max:30',
                'regex:/^[0-9]+$/u',
            ],
            'career' => 'string|required|max:30|in:' . implode(',', self::careerAcceptedOptions()),
            'sim_contractor' => 'string|required|max:30',
            'status' => 'string|nullable|max:1|in:1,2,3,4,5',
            'sim_opening_date' => 'date|required',
        ];
    }

    public function line() {
        return $this->hasOne(MUserLine::class, 'sim_num')->withDefault();
    }

    public static function careerAcceptedOptions() {
        return ['Docomo', 'Soft Bank', 'Au', 'D1'];
    }
}
