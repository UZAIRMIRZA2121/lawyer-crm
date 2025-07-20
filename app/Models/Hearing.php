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
        'priority',
         'nature',
    ];

    // Relation to case
    public function case()
    {
        return $this->belongsTo(CaseModel::class);
    }
}
