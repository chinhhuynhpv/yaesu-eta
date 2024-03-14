<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MUserLineTalkGroupAdditionalRequests extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_user_line_talk_group_add_req';
}
