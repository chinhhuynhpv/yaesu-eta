<?php

namespace App\Models;

use App\Traits\HandleInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TUserLinePlanRequest extends Model
{
    use HandleInput;
    use HasFactory;
    use SoftDeletes;

    protected $table = 't_user_line_plans_requests';

    /**
     * Get the plan associated with the table.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(MPlan::class, 'plan_id')->withDefault();
    }

    public function getOption($column)
    {
        return $this->belongsTo(MOptionPlan::class, $column)->withDefault();
    }

    /**
     * Get the plan associated with the table.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line_request()
    {
        return $this->belongsTo(MUserLineRequest::class, 'line_id')->withDefault();
    }

    public function __get($key)
    {
        $value = parent::__get($key); // TODO: Change the autogenerated stub

        if (in_array($key, ['automatic_update'])) {
            return $this->getAutomaticUpdateColumn($value);
        }

        return $value;
    }

    private function getAutomaticUpdateColumn($value)
    {
        switch ($value) {
            case '1':
                return __('no');
            case '2':
                return __('yes');
        }
    }
}
