<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseAgainstClient extends Model
{

    protected $fillable = [
        'case_id',
        'name',
        'cnic',
        'address',
        'phone',
    ];

    public function case()
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }
}
