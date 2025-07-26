<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{

    protected $fillable = [
    'case_id',
    'user_id',
    'against_client_id',
    'notice',
    'status',
    'notice_base64',
    ];
    // If you have a Case model:
    public function case()
    {
        return $this->belongsTo(CaseModel::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
      public function against_client()
    {
        return $this->belongsTo(CaseAgainstClient::class ,'against_client_id');
    }
    
}
