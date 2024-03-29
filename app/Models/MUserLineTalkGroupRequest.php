<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MUserTalkGroupRequest;
use Illuminate\Database\Eloquent\SoftDeletes;

class MUserLineTalkGroupRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_user_line_talk_group_requests';

    /**
     * Get lineIds associated with the request.
     */
    public function additional_groups()
    {
        return $this->hasMany(MUserLineTalkGroupAdditionalRequests::class, 'line_group_req_id');
    }

    static public function columnConstraints()
    {
        return [
            "requestId" => "required",
            "userId" => "required",
            "shopId" => "required",
            "lineId" => "required|max:30",
            "lineNum" => "required|max:255",
            "voipIdName" => "required|max:255",
            "groupMain" => "required|max:255"
        ];
    }

    public function __get($key)
    {
        $value = parent::__get($key); // TODO: Change the autogenerated stub

        if (in_array($key, ['request_type'])) {
            return $this->getRequestTypeColumn($value);
        }

        return $value;
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
