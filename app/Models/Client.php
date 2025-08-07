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
    'cnic_front',
    'cnic_back',
    'referral_by', // ✅ Updated to use referral_by
];
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'client_user');
    }
    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'client_id');
    }

}
