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
        'final_letter',
        'processed_at',
        'processed_by',
    ];

    protected $appends = ['final_letter_url'];

    public function getFinalLetterUrlAttribute()
    {
        return $this->final_letter ? asset('storage/' . $this->final_letter) : null;
    }

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
