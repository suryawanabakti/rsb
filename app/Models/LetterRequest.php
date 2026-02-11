<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Patient;
use App\Models\LetterType;
use App\Models\RequestFile;

class LetterRequest extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'patient_id',
        'letter_type_id',
        'submission_date',
        'status',
        'admin_notes',
        'processed_at',
        'processed_by',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function letterType()
    {
        return $this->belongsTo(LetterType::class);
    }

    public function files()
    {
        return $this->hasMany(RequestFile::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
