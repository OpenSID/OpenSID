<?php

namespace Database\Factories\Modules\Analisis\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Analisis\Models\AnalisisIndikator;

class AnalisisIndikatorFactory extends Factory
{
    protected $model = AnalisisIndikator::class;

    public function definition(): array
    {
        return [
            'nomor'       => (string) $this->faker->numberBetween(1, 100),
            'pertanyaan'  => $this->faker->sentence(),
            'is_publik'   => true,
            'is_teks'     => false,
            'id_tipe'     => $this->faker->numberBetween(1, 4),
            'bobot'       => $this->faker->numberBetween(0, 100),
            'act_analisis' => 2,
        ];
    }

    public function nonPublik(): static
    {
        return $this->state(['is_publik' => false]);
    }
}
