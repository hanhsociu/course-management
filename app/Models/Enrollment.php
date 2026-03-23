<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = ['user_id', 'course_id', 'enrolled_at'];

    // Ép kiểu ngày tháng
    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    // Enrollment thuộc về 1 User [cite: 25]
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Enrollment thuộc về 1 Course [cite: 25]
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
