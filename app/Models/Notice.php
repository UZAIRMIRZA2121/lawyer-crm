<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{

    protected $fillable = [
        'case_id',
        'notice',
        'status',
    ];

    // If you have a Case model:
    public function case()
    {
        return $this->belongsTo(CaseModel::class);
    }
}
