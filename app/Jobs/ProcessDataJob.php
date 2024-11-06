<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    public $tries = 3;  // Número de intentos antes de marcar el job como fallido
    public $backoff = 30;  // Retraso de 30 segundos entre cada intento
    /**
     * Crear una nueva instancia del trabajo.
     *
     * @param  mixed  $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Ejecutar el trabajo.
     *
     * @return void
     */
    public function handle()
    {
        // Simula una tarea prolongada
        Log::info("Procesando datos: " . $this->data);
        sleep(5); // Simula un retardo de 5 segundos
        Log::info("Datos procesados: " . $this->data);
    }
}
