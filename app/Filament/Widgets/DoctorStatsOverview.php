<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DoctorStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Indicadores del médico';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'hasRole') || ! $user->hasRole('Doctor')) {
            return [];
        }

        // Asumimos que el email del usuario coincide con el del doctor
        $doctor = Doctor::where('email', $user->email)->first();

        if (! $doctor) {
            return [];
        }

        $cacheKey = "dashboard:doctor:stats:{$doctor->id}";

        $metrics = Cache::remember($cacheKey, 60, function () use ($doctor) {
            $now = now();
            $thirtyDaysAgo = $now->copy()->subDays(30);

            // Citas completadas en el último mes
            $completedLast30 = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$thirtyDaysAgo, $now])
                ->count();

            // Citas pendientes
            $pendingAppointments = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->whereIn('status', ['pending', 'accepted'])
                ->where('scheduled_date', '>', $now)
                ->count();

            // Total de citas solicitadas en el último mes
            $totalRequestedLast30 = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->whereBetween('created_at', [$thirtyDaysAgo, $now])
                ->count();

            // Citas aceptadas en el último mes
            $acceptedLast30 = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->where('status', 'accepted')
                ->whereBetween('accepted_at', [$thirtyDaysAgo, $now])
                ->count();

            // Pacientes únicos atendidos en el último mes
            $uniquePatientsLast30 = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$thirtyDaysAgo, $now])
                ->distinct('patient_id')
                ->count('patient_id');

            // Tasa de aceptación
            $acceptanceRate = $totalRequestedLast30 > 0
                ? round(($acceptedLast30 / $totalRequestedLast30) * 100, 1)
                : null;

            return [
                'completedLast30' => $completedLast30,
                'pendingAppointments' => $pendingAppointments,
                'acceptanceRate' => $acceptanceRate,
                'uniquePatientsLast30' => $uniquePatientsLast30,
                'specialty' => $doctor->specialty,
            ];
        });

        return [
            Stat::make('Citas completadas (último mes)', number_format($metrics['completedLast30']))
                ->description('Pacientes atendidos exitosamente')
                ->color('success'),
            Stat::make('Citas pendientes', number_format($metrics['pendingAppointments']))
                ->description('Próximas citas programadas')
                ->color('info'),
            Stat::make('Pacientes únicos atendidos', number_format($metrics['uniquePatientsLast30']))
                ->description('En el último mes')
                ->color('primary'),
            Stat::make('Tasa de aceptación', $metrics['acceptanceRate'] !== null ? $metrics['acceptanceRate'].'%' : 'Sin datos')
                ->description('De solicitudes de cita en el último mes')
                ->color($this->resolveAcceptanceColor($metrics['acceptanceRate'])),
        ];
    }

    protected function resolveAcceptanceColor(?float $rate): string
    {
        if ($rate === null) {
            return 'secondary';
        }

        if ($rate >= 80) {
            return 'success';
        }

        if ($rate >= 60) {
            return 'warning';
        }

        return 'danger';
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') && $user->hasRole('Doctor');
    }
}
