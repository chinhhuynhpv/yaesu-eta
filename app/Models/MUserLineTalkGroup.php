<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MUserLineTalkGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'm_user_line_talk_group';

    static public function columnConstraints()
    {
        return [
            "lineId" => "required|max:30",
            "lineNum" => "required|max:255",
            "voipIdName" => "required|max:255",
            "groupMain" => "required|max:255"
        ];
    }

    public function line()
    {
        return $this->belongsTo(MUserLine::class, 'line_id');
    }

    public function talkGroup()
    {
        return $this->belongsTo(MUserTalkGroup::class, 'talk_group_id');
    }
}
