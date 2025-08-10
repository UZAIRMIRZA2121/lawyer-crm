<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUpload extends Model
{
    use HasFactory;

    protected $table = 'task_uploads'; // optional if follows naming convention

    protected $fillable = [
        'task_id',
        'user_id',
        'upload_files',
    ];

    // Relationships (optional, add if you want)

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
