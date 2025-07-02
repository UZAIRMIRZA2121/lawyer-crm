<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{

    protected $table = 'case_models';

    protected $fillable = [
        'case_number',
        'client_id',
        'case_title',
        'description',
        'status',
        'hearing_date',
        'judge_name',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function files()
{
    return $this->hasMany(\App\Models\CaseFile::class, 'case_id');
}
}
