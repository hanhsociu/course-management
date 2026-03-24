<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    // BẮT BUỘC THÊM DÒNG NÀY ĐỂ ÉP LARAVEL CHỈ ĐÚNG BẢNG
    protected $table = 'progresses';

    protected $fillable = ['user_id', 'lesson_id', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
