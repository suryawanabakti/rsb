<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RequestFile extends Model
{
    protected $fillable = [
        'letter_request_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_at',
    ];

    protected $appends = ['file_url', 'original_name'];

    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset(Storage::url($this->file_path)) : null;
    }

    public function getOriginalNameAttribute()
    {
        return $this->file_name;
    }
}
