<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pair>
 */
class PairFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public static $PAIRS = [];
    public function definition()
    {
        $currencies = Currency::all()->toArray();
        $currency_from = $currencies[array_rand($currencies)];
        // delete currency_from from list of currencies
        unset($currencies[array_search($currency_from, $currencies)]);
        $currency_to = $currencies[array_rand($currencies)];

        $name = $currency_from['code'] . '_' . $currency_to['code'];
        return [
            'name' => $name,
            'rate' => $this->faker->randomFloat(2, 0, 100),
            'currency_id_from' => $currency_from['id'],
            'currency_id_to' => $currency_to['id'],
        ];
    }
}
