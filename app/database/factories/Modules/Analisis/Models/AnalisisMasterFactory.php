<?php

namespace Database\Factories\Modules\Analisis\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Analisis\Models\AnalisisMaster;

class AnalisisMasterFactory extends Factory
{
    protected $model = AnalisisMaster::class;

    public function definition(): array
    {
        return [
            'nama'            => $this->faker->word(),
            'subjek_tipe'     => $this->faker->numberBetween(1, 4),
            'lock'            => false,
            'deskripsi'       => $this->faker->sentence(),
            'kode_analisis'   => $this->faker->numerify('#####'),
            'pembagi'         => '100',
        ];
    }

    public function locked(): static
    {
        return $this->state(['lock' => true]);
    }
}
