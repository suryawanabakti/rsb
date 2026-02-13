<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'nik',
        'address',
        'birth_date',
        'gender',
        'pangkat',
        'nrp_nip',
        'pendidikan_terakhir',
        'jabatan_kesatuan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function letterRequests()
    {
        return $this->hasMany(LetterRequest::class);
    }
}
