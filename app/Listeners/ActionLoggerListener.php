<?php

namespace App\Listeners;

use App\Events\ActionLoggerEvent;
use App\Models\Logger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ActionLoggerListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ActionLoggerEvent $event): void
    {
        Logger::create([
            'action' => $event->action,
            'model' => $event->model,
            'user_id' => $event->userId->id,
        ]);
    }
}
