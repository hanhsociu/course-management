<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = ['user_id', 'lesson_id', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Tiến độ này là của 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tiến độ track theo từng Lesson [cite: 27]
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
