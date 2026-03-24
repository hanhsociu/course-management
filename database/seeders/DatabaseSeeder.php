<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Course;
use App\Models\Lesson;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Tạo 10 khóa học, mỗi khóa có 5 bài học
        Course::factory(10)->has(
            Lesson::factory()->count(5)
        )->create();
    }
}
