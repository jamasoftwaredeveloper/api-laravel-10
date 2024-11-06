<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Crea una nueva instancia del job.
     *
     * @return void
     */
    public function __construct()
    {
        // Puedes agregar parámetros aquí si el job necesita datos
    }

    /**
     * Ejecutar el trabajo.
     *
     * @return void
     */
    public function handle()
    {
        // Acción que quieres realizar
        Log::info('¡El job NotifyJob ha sido ejecutado correctamente!');
    }
}
