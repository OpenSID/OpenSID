<?php

namespace Database\Factories\Modules\Analisis\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Analisis\Models\AnalisisPeriode;

class AnalisisPeriodeFactory extends Factory
{
    protected $model = AnalisisPeriode::class;

    public function definition(): array
    {
        return [
            'id_state'          => 1,
            'nama'              => $this->faker->word(),
            'keterangan'        => $this->faker->sentence(),
            'tahun_pelaksanaan' => (string) $this->faker->year(),
            'aktif'             => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['aktif' => false]);
    }
}
