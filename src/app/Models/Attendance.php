<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'breaks',
        'note',
        'is_requesting',
    ];

    protected $casts = [
        'breaks' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
