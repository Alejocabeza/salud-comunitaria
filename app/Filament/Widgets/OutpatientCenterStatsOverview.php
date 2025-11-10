<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithOutpatientCenter;
use App\Models\Appointment;
use App\Models\MedicationRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OutpatientCenterStatsOverview extends StatsOverviewWidget
{
    use InteractsWithOutpatientCenter;

    protected static ?int $sort = 1;

    protected ?string $heading = 'Indicadores clave del centro';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $center = $this->resolveOutpatientCenter();

        if (! $center) {
            return [];
        }

        $cacheKey = "dashboard:outpatient-center:stats:{$center->id}";

        $metrics = Cache::remember($cacheKey, 60, function () use ($center) {
            $now = now();
            $todayStart = $now->copy()->startOfDay();
            $todayEnd = $now->copy()->endOfDay();
            $startOfWeek = $now->copy()->startOfWeek();
            $thirtyDaysAgo = $now->copy()->subDays(30);

            $scheduledToday = Appointment::query()
                ->where('outpatient_center_id', $center->id)
                ->whereBetween('scheduled_date', [$todayStart, $todayEnd])
                ->whereIn('status', ['pending', 'accepted', 'completed'])
                ->count();

            $completedWeek = Appointment::query()
                ->where('outpatient_center_id', $center->id)
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$startOfWeek, $now])
                ->count();

            $scheduledLast30 = Appointment::query()
                ->where('outpatient_center_id', $center->id)
                ->whereBetween('scheduled_date', [$thirtyDaysAgo, $now])
                ->count();

            $noshowCount = Appointment::query()
                ->where('outpatient_center_id', $center->id)
                ->whereIn('status', ['pending', 'accepted'])
                ->where('scheduled_date', '<', $now)
                ->whereNull('completed_at')
                ->count();

            $durations = Appointment::query()
                ->where('outpatient_center_id', $center->id)
                ->whereNotNull('completed_at')
                ->whereBetween('completed_at', [$thirtyDaysAgo, $now])
                ->whereNotNull('scheduled_date')
                ->get(['scheduled_date', 'completed_at'])
                ->map(fn (Appointment $appointment): int => $appointment->scheduled_date->diffInMinutes($appointment->completed_at, true));

            $pendingMedicationRequests = MedicationRequest::query()
                ->where('outpatient_center_id', $center->id)
                ->where('status', MedicationRequest::STATUS_PENDING)
                ->count();

            $noShowRate = $scheduledLast30 > 0
                ? round(($noshowCount / $scheduledLast30) * 100, 1)
                : null;

            $averageWait = $durations->isNotEmpty()
                ? round($durations->avg())
                : null;

            return [
                'scheduledToday' => $scheduledToday,
                'completedWeek' => $completedWeek,
                'noShowRate' => $noShowRate,
                'averageWaitMinutes' => $averageWait,
                'pendingMedicationRequests' => $pendingMedicationRequests,
            ];
        });

        return [
            Stat::make('Citas programadas hoy', number_format($metrics['scheduledToday']))
                ->description('Pacientes agendados o en curso para hoy')
                ->color('info'),
            Stat::make('Citas completadas (semana)', number_format($metrics['completedWeek']))
                ->description('Del inicio de semana a la fecha')
                ->color('success'),
            Stat::make('Solicitudes de medicamentos pendientes', number_format($metrics['pendingMedicationRequests']))
                ->description('Requieren revisión del centro')
                ->color($metrics['pendingMedicationRequests'] > 0 ? 'danger' : 'success'),
        ];
    }

    protected function formatMinutes(?float $minutes): string
    {
        if ($minutes === null) {
            return 'Sin datos';
        }

        $totalMinutes = (int) round($minutes);

        if ($totalMinutes < 60) {
            return $totalMinutes.' min';
        }

        $hours = intdiv($totalMinutes, 60);
        $remaining = $totalMinutes % 60;

        if ($remaining === 0) {
            return $hours.' h';
        }

        return $hours.' h '.$remaining.' min';
    }

    protected function resolveNoShowColor(?float $value): string
    {
        if ($value === null) {
            return 'secondary';
        }

        if ($value < 10) {
            return 'success';
        }

        if ($value < 20) {
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

        return method_exists($user, 'hasRole') && $user->hasRole('Manager');
    }
}
