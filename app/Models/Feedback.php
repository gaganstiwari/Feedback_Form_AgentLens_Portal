<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{

    protected $table = 'feedbacks';

    protected $fillable = [
        'request_id',
        'nps_score',
        'iscompleted',
        'feedback',
        'comment',
        'status',

    ];

    protected $casts = [
        'feedback' => 'array',           // JSON field
        'is_completed' => 'integer',     // ⭐ Cast to integer (0 or 1)
        'nps_score' => 'integer',
        'request_id' => 'string',
    ];


}