<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TActionLog extends Model
{
    use HasFactory;

    protected $table = 't_action_logs';

    protected $fillable = ['controller','method','action','parameter','user_id', 'ip', 'user_type', 'shop_id', 'is_admin'];
}
