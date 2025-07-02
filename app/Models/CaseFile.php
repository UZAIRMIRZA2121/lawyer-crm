<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    protected $fillable = [
        'case_id',
        'user_id',
        'file_path',
        'sequence',
    ];

    public function case()
    {
        return $this->belongsTo(CaseModel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
