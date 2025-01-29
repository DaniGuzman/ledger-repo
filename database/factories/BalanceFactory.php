<?php

namespace Database\Factories;

use App\Models\balance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BalanceFactory extends Factory
{
    protected $model = balance::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'balance' => $this->faker->randomNumber(),
        ];
    }
}
