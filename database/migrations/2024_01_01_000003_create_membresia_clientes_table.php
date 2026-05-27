<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membresia_clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('membresia_id')->constrained('membresias')->cascadeOnDelete();
            $table->foreignId('vendido_por')->constrained('users')->cascadeOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('precio_pagado', 10, 2);
            $table->enum('estado', ['activa', 'cancelada', 'expirada'])->default('activa');
            $table->timestamps();

            $table->unique(['cliente_id', 'membresia_id', 'fecha_inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membresia_clientes');
    }
};