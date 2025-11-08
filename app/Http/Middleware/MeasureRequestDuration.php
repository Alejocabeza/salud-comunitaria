<?php

namespace App\Http\Middleware;

use App\Models\Logger;
use Closure;
use Illuminate\Http\Request;

class MeasureRequestDuration
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $durationMs = (microtime(true) - $start) * 1000;

        try {
            Logger::create([
                'action' => 'request.duration',
                'model' => $request->path(),
                'user_id' => auth()->id(),
                'level' => $durationMs > 2000 ? 'warning' : 'info',
                'message' => sprintf('%.2fms', $durationMs),
                'context' => [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                ],
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        return $response;
    }
}
