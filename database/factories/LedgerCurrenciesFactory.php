<?php

namespace Database\Factories;

use App\Models\LedgerCurrencies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LedgerCurrenciesFactory extends Factory
{
    protected $model = LedgerCurrencies::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
