<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Ajusta los tipos si tus columnas tienen otra longitud/tipo
        DB::statement("ALTER TABLE `usuarios` MODIFY `numeroRFID` VARCHAR(255) NULL");
        DB::statement("ALTER TABLE `usuarios` MODIFY `puntos` INT UNSIGNED NULL");
    }

    public function down(): void
    {
        // Revertir a NOT NULL si así estaban originalmente
        DB::statement("ALTER TABLE `usuarios` MODIFY `numeroRFID` VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE `usuarios` MODIFY `puntos` INT UNSIGNED NOT NULL");
    }
};
