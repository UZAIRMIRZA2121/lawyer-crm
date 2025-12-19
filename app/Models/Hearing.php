<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hearing extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'judge_name',
        'judge_remarks',
        'my_remarks',
        'next_hearing',
        'status',       // ✅ Add status before priority
        'priority',
        'nature',
         'talbi', // ✅ added
    ];

    // Relation to case
    public function case()
    {
        return $this->belongsTo(CaseModel::class);
    }
}
