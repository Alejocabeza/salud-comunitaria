<?php

namespace App\Exceptions;

use App\Models\Logger;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function report(Throwable $exception): void
    {
        try {
            // Persist a concise representation to the Logger table without blocking
            Logger::create([
                'action' => 'exception',
                'model' => get_class($exception),
                'user_id' => auth()->id(),
                'level' => 'error',
                'message' => $exception->getMessage(),
                'context' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
                'trace' => $exception->getTraceAsString(),
            ]);
        } catch (Throwable $e) {
            // If logging to DB fails, ignore to avoid breaking exception handling
        }

        parent::report($exception);
    }
}
