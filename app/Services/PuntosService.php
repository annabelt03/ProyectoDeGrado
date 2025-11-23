<?php
// app/Services/PuntosService.php
namespace App\Services;

class PuntosService
{
    /**
     * Calcula puntos según:
     *  - 0.01 kg => 0.5 pts
     *  - 0.1  kg => 1.0 pt
     *  - lineal a partir de eso, y tope (cap) en 10 pts
     *  - si peso < 0, puntos = 0 (pero el peso se guarda tal cual)
     */
    public function calcular(float $pesoKg): float
    {
        if ($pesoKg <= 0) {
            return 0.0;
        }

        // Recta que pasa por (0.01, 0.5) y (0.1, 1.0)
        // m = (1.0 - 0.5) / (0.1 - 0.01) = 0.5 / 0.09 = 5.555...
        // b = y - m x  =>  0.5 - 5.555...*0.01 = 0.444...
        $m = 0.5 / 0.09;            // ≈ 5.5555555556
        $b = 0.5 - $m * 0.01;       // ≈ 0.4444444444

        $puntos = $m * $pesoKg + $b;

        // Cap máximo 10
        if ($puntos > 10) {
            $puntos = 10.0;
        }

        // Nunca negativo
        if ($puntos < 0) {
            $puntos = 0.0;
        }

        // Redondeo a 2 decimales para almacenar bonito
        return round($puntos, 2);
    }
}
