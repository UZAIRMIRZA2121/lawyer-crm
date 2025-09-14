<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'case_id',
        'amount',
        'type',
        'payment_method',
        'transaction_date',
        'description',
        'status',
        'sub_status',
    ];
       public function case()
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

}
