<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Thêm dòng này
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Lesson extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Thêm 'video_url' vào fillable để khung sẵn sàng nhận dữ liệu video
    protected $fillable = ['course_id', 'title', 'content', 'order', 'video_url', 'is_preview'];

    protected $casts = [
        'order' => 'integer',
        'is_preview' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
