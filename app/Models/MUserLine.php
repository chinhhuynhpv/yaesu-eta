<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HandleSearch;
use App\Traits\HandleInput;

class MUserLine extends Model
{
    use HasFactory;
    use HandleInput;
    use SoftDeletes;
    use HandleSearch;

    protected $table = 'm_user_lines';
    protected $search = ['line_num', 'sim_num'];

    public function line_talk_groups()
    {
        return $this->hasMany(MUserLineTalkGroup::class, 'line_id');
    }

    static public function generateContracId()
    {
        $lastRquest = self::orderBy('id', 'DESC')->first();

        return sprintf("%06d", $lastRquest->id + 1);
    }

    public function user_line_plan() {
        return $this->hasOne(TUserLinePlan::class, 'line_id')->withDefault();

    }

    static public function columnConstraints()
    {
        return [
            "voipLineId" => "required|max:255|regex:/^[0-9]+$/u",
            "voipIdName" => "required|max:255",
            "priority" => "required",
            "voipLinePassword" => "nullable|max:255",
            'startDate'  =>  'required|date',
            'changeApplicationDate'  =>  'nullable|date',
            'contractRenewalDate'  =>  'nullable|date',
            'startDateOfPlan'=>  'required|date',
        ];
    }

    public function getCallTypeAttribute($value)
    {
        return $value == '1' ? __('Simple') : __('Full duflex');
    }

    public function __get($key)
    {
        $value = parent::__get($key); // TODO: Change the autogenerated stub

        if (in_array($key, ['individual', 'recording', 'gps', 'commander', 'individual_priority', 'cue_reception', 'video'])) {
            return $this->getOnOffColumn($value);
        }

        if (in_array($key, ['request_type'])) {
            return $this->getRequestTypeColumn($value);
        }

        if (in_array($key, ['priority'])) {
            return $this->getPriorityColumn($value);
        }

        if (in_array($key, ['status'])) {
            return $this->getStatusColumn($value);
        }

        return $value;
    }

    private function getOnOffColumn($value)
    {
        switch ($value) {
            case '1':
                return __('on');
            case '2':
                return __('off');
        }
    }

    private function getStatusColumn($value)
    {
       
        switch ($value) {
            case '1':
                return __('in use');
            case '2':
                return __('in active');
            case '3':
                return __('discontinued');
        }
    }

    private function getPriorityColumn($value)
    {
        switch ($value) {
            case '1':
                return __('Level 1');
            case '2':
                return __('Level 2');
            case '3':
                return __('Level 3');
            case '4':
                return __('Level 4');
            case '5':
                return __('Level 5');
            case '6':
                return __('Level 6');
            case '7':
                return __('Level 7');
            case '8':
                return __('Level 8');
            case '9':
                return __('Level 9');
            case '10':
                return __('Level 10');
        }
    }

    private function getRequestTypeColumn($value)
    {
        switch ($value) {
            case '1':
                return __('Add');
            case '2':
                return __('Modify');
            case '3':
                return __('Pause');
            case '4':
                return __('Restart');
            case '5':
                return __('Discontinued');
        }
    }
}