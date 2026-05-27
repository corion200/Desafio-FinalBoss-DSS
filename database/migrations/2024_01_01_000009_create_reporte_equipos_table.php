<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('users')->cascadeOnDelete();
            $table->string('equipo_nombre', 150);
            $table->string('ubicacion', 150)->nullable();
            $table->text('descripcion');
            $table->enum('estado', ['pendiente', 'en_reparacion', 'resuelto'])->default('pendiente');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
            $table->date('fecha_resolucion')->nullable();
            $table->text('resolucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_equipos');
    }
};