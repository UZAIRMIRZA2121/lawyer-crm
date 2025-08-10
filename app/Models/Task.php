<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'user_id',
        'title',          // Add this line
        'task',
        'priority',
        'submit_date',
        'status',
        'sub_status',
        'group_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
