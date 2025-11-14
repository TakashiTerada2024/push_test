<?php

namespace Database\Factories;

use App\Models\ApplyMemo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplyMemoFactory extends Factory
{
    protected $model = ApplyMemo::class;

    public function definition()
    {
        return [
            'apply_id' => \App\Models\Apply::factory(),
            'memo' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
