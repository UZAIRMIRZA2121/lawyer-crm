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
         'priority', // ✅ added here after status
        'hearing_date',
        'judge_name',
        'case_nature',
        'amount', // ✅ add this line
        'commission_amount', // ✅ add this line
        'created_at', // ✅ add this line
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function files()
    {
        return $this->hasMany(\App\Models\CaseFile::class, 'case_id');
    }
    public function hearings()
    {
        return $this->hasMany(Hearing::class, 'case_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'case_id');
    }
    public function assignedUsers()
    {

        return $this->client->assignedUsers()->wherePivot('case_id', $this->id);
    }
    public function againstClients()
    {
        return $this->hasMany(CaseAgainstClient::class, 'case_id'); // 👈 specify correct foreign key
    }
    public function notices()
    {
        return $this->hasMany(Notice::class, 'case_id');
    }
}
