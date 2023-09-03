<?php

namespace App\Http\Traits;

trait GlobalTrait
{
    /**
     * Maneja las excepciones de manera uniforme y genera una respuesta JSON.
     *
     * @param \Exception $ex
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleException(\Exception $ex, $message = 'Algo salió mal')
    {
        return response()->json(['error' => $message, 'details' => $ex->getMessage(),  'line' =>$ex->getLine()], 100);
    }

    /**
     * Comprueba si el producto está inactivo.
     *
     * @return bool
     */
    public function isInactive()
    {
        return $this->active === 0;
    }
}
