<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(4), // Tạo tên khóa học ngẫu nhiên
            'description' => fake()->paragraph(3), // Tạo mô tả
            'price' => fake()->randomElement([0, 299000, 499000, 999000]), // Giá tiền ngẫu nhiên
            'status' => fake()->boolean(80), // 80% tỷ lệ là Active (1)
        ];
    }
}
