<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DoctorsOverviewStats extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Estadísticas generales de médicos';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'hasRole') || ! $user->hasRole('Manager')) {
            return [];
        }

        $cacheKey = 'dashboard:managers:doctors-overview';

        $metrics = Cache::remember($cacheKey, 60, function () {
            $now = now();
            $thirtyDaysAgo = $now->copy()->subDays(30);

            // Total de doctores activos
            $activeDoctors = Doctor::where('is_active', true)->count();

            // Total de citas completadas en el último mes
            $totalCompletedAppointments = Appointment::query()
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$thirtyDaysAgo, $now])
                ->count();

            // Promedio de citas por doctor
            $avgAppointmentsPerDoctor = $activeDoctors > 0
                ? round($totalCompletedAppointments / $activeDoctors, 1)
                : 0;

            // Doctores más activos (top 3)
            $topDoctors = Doctor::query()
                ->withCount(['appointments' => function ($query) use ($thirtyDaysAgo, $now) {
                    $query->where('status', 'completed')
                        ->whereBetween('completed_at', [$thirtyDaysAgo, $now]);
                }])
                ->orderBy('appointments_count', 'desc')
                ->take(3)
                ->get();

            $topDoctorName = $topDoctors->first()?->full_name ?? 'Sin datos';

            // Especialidades más comunes
            $topSpecialty = Doctor::query()
                ->whereNotNull('specialty')
                ->selectRaw('specialty, COUNT(*) as count')
                ->groupBy('specialty')
                ->orderBy('count', 'desc')
                ->first();

            return [
                'activeDoctors' => $activeDoctors,
                'totalCompletedAppointments' => $totalCompletedAppointments,
                'avgAppointmentsPerDoctor' => $avgAppointmentsPerDoctor,
                'topDoctorName' => $topDoctorName,
                'topSpecialty' => $topSpecialty?->specialty ?? 'Sin datos',
            ];
        });

        return [
            Stat::make('Médicos activos', number_format($metrics['activeDoctors']))
                ->description('Profesionales disponibles')
                ->color('primary'),
            Stat::make('Citas completadas (último mes)', number_format($metrics['totalCompletedAppointments']))
                ->description('Total en todos los centros')
                ->color('success'),
            Stat::make('Promedio de citas por médico', $metrics['avgAppointmentsPerDoctor'])
                ->description('En el último mes')
                ->color('info'),
            Stat::make('Especialidad más común', $metrics['topSpecialty'])
                ->description('Entre los médicos activos')
                ->color('secondary'),
        ];
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') && $user->hasRole('Manager');
    }
}
