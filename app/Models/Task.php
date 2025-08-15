<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',      // Task title
        'task',
        'priority',
        'submit_date',
        'status',
        'sub_status',
        'group_id',
    ];

    // Task belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Task has many uploads
    public function uploads()
    {
        return $this->hasMany(TaskUpload::class, 'task_id', 'id');
    }
}
