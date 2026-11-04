<?php

namespace Database\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CapsuleFactory extends Factory
{
    public function definition(): array
    {
        return ['title' => fake()->sentence(3), 'body' => 'Pesan percobaan yang tetap rahasia.', 'token_hash' => hash('sha256', Str::random(64)), 'opens_at' => CarbonImmutable::now('UTC')->addDay()];
    }
}
