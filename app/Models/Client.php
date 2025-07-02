<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnic',
        'contact_no',
        'email',
        'address',
        'cnic_front', // ✅ Add this
        'cnic_back',  // ✅ And this
    ];
}
