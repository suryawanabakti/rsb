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
        'nomor_surat',
        'keperluan',
        'notes',
        'admin_notes',
        'pemeriksaan_data',
        'final_letter',
        'processed_at',
        'processed_by',
        'dokter_pemeriksa_id',
    ];

    protected $casts = [
        'pemeriksaan_data' => 'array',
        'submission_date' => 'date',
        'processed_at' => 'datetime',
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

    public function dokterPemeriksa()
    {
        return $this->belongsTo(User::class, 'dokter_pemeriksa_id');
    }
}
