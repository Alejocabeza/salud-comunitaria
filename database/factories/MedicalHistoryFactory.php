<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalHistoryFactory extends Factory
{
    protected $model = MedicalHistory::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'type' => $this->faker->randomElement(['consulta', 'diagnóstico', 'examen', 'medicación']),
            'date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'summary' => $this->faker->sentence(6),
            'notes' => $this->faker->paragraph(),
            'related_diseases' => null,
            'attachments' => null,
            'created_by' => User::factory(),
        ];
    }
}
