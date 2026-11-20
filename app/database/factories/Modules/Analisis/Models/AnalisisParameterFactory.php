<?php

namespace Database\Factories\Modules\Analisis\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Analisis\Models\AnalisisParameter;

class AnalisisParameterFactory extends Factory
{
    protected $model = AnalisisParameter::class;

    public function definition(): array
    {
        return [
            'jawaban'      => $this->faker->word(),
            'nilai'        => $this->faker->numberBetween(0, 100),
            'kode_jawaban' => $this->faker->numberBetween(1, 99),
            'asign'        => false,
        ];
    }
}
