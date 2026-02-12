<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'letter_request_id',
        'test_name',
        'test_date',
        'result_data',
        'notes',
        'status',
        'inputted_by',
        'validated_by',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'result_data' => 'array',
            'test_date' => 'date',
            'validated_at' => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function letterRequest()
    {
        return $this->belongsTo(LetterRequest::class);
    }

    public function inputter()
    {
        return $this->belongsTo(User::class, 'inputted_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
