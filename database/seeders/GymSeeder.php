<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GymSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== USUARIOS ====================
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@gym.com',
            'password' => Hash::make('12345678'),
            'role'     => 'admin',
            'telefono' => '5555-0000',
            'activo'   => true,
        ]);

        User::create([
            'name'     => 'María López',
            'email'    => 'maria@gym.com',
            'password' => Hash::make('12345678'),
            'role'     => 'recepcionista',
            'telefono' => '5555-0001',
            'activo'   => true,
        ]);

        $coach1 = User::create([
            'name'     => 'Carlos Pérez',
            'email'    => 'carlos@gym.com',
            'password' => Hash::make('12345678'),
            'role'     => 'coach',
            'telefono' => '5555-0002',
            'activo'   => true,
        ]);

        $coach2 = User::create([
            'name'     => 'Ana Rodríguez',
            'email'    => 'ana@gym.com',
            'password' => Hash::make('12345678'),
            'role'     => 'coach',
            'telefono' => '5555-0003',
            'activo'   => true,
        ]);

        // ==================== MEMBRESÍAS ====================
        Membresia::create([
            'nombre'        => 'Diaria',
            'duracion_dias' => 1,
            'precio'        => 5.00,
            'descripcion'   => 'Acceso por un día',
            'activa'        => true,
        ]);

        Membresia::create([
            'nombre'        => 'Semanal',
            'duracion_dias' => 7,
            'precio'        => 25.00,
            'descripcion'   => 'Acceso por una semana completa',
            'activa'        => true,
        ]);

        Membresia::create([
            'nombre'        => 'Mensual',
            'duracion_dias' => 30,
            'precio'        => 80.00,
            'descripcion'   => 'Acceso por un mes, la más popular',
            'activa'        => true,
        ]);

        Membresia::create([
            'nombre'        => 'Trimestral',
            'duracion_dias' => 90,
            'precio'        => 200.00,
            'descripcion'   => 'Acceso por tres meses con descuento',
            'activa'        => true,
        ]);

        Membresia::create([
            'nombre'        => 'Anual',
            'duracion_dias' => 365,
            'precio'        => 600.00,
            'descripcion'   => 'Acceso por un año, el mejor precio',
            'activa'        => true,
        ]);

        // ==================== PRODUCTOS ====================
        Producto::create([
            'nombre'      => 'Proteína Whey Gold 2lbs',
            'descripcion' => 'Suplemento de proteína de suero de leche, sabor vainilla.',
            'precio'      => 45.00,
            'stock'       => 25,
            'categoria'   => 'suplemento',
            'activo'      => true,
        ]);

        Producto::create([
            'nombre'      => 'Creatina Monohidrato 500g',
            'descripcion' => 'Creatina pura de alta calidad para rendimiento.',
            'precio'      => 30.00,
            'stock'       => 15,
            'categoria'   => 'suplemento',
            'activo'      => true,
        ]);

        Producto::create([
            'nombre'      => 'BCAA Powder 400g',
            'descripcion' => 'Aminoácidos ramificados, sabor frutas del bosque.',
            'precio'      => 28.00,
            'stock'       => 12,
            'categoria'   => 'suplemento',
            'activo'      => true,
        ]);

        Producto::create([
            'nombre'      => 'Mancuernas Ajustables 20kg',
            'descripcion' => 'Par de mancuernas ajustables con sistema de disco rápido.',
            'precio'      => 120.00,
            'stock'       => 5,
            'categoria'   => 'equipamiento',
            'activo'      => true,
        ]);

        Producto::create([
            'nombre'      => 'Cuerda para Saltar Pro',
            'descripcion' => 'Cuerda de velocidad con rodamientos de balero.',
            'precio'      => 15.00,
            'stock'       => 20,
            'categoria'   => 'accesorio',
            'activo'      => true,
        ]);

        Producto::create([
            'nombre'      => 'Guantes de Gimnasio',
            'descripcion' => 'Guantes con muñequera para levantamiento de pesas.',
            'precio'      => 18.00,
            'stock'       => 30,
            'categoria'   => 'accesorio',
            'activo'      => true,
        ]);

        Producto::create([
            'nombre'      => 'Camiseta DryFit Gym',
            'descripcion' => 'Camiseta deportiva con tecnología de secado rápido.',
            'precio'      => 22.00,
            'stock'       => 0,
            'categoria'   => 'ropa',
            'activo'      => true,
        ]);

        Producto::create([
            'nombre'      => 'Bandas de Resistencia Set',
            'descripcion' => 'Set de 5 bandas con diferentes niveles de resistencia.',
            'precio'      => 25.00,
            'stock'       => 18,
            'categoria'   => 'accesorio',
            'activo'      => true,
        ]);

        // ==================== CLIENTES DE EJEMPLO ====================
        $clientes = [
            ['Juan', 'García', '001-1234567-8', 'juan@email.com', '5555-1001', '1995-03-15', null, $coach1->id],
            ['Laura', 'Martínez', '002-2345678-9', 'laura@email.com', '5555-1002', '1998-07-22', null, $coach1->id],
            ['Pedro', 'Sánchez', '003-3456789-0', 'pedro@email.com', '5555-1003', '1990-11-08', null, $coach2->id],
            ['Sofía', 'Hernández', '004-4567890-1', 'sofia@email.com', '5555-1004', '2001-01-30', null, $coach2->id],
            ['Diego', 'Torres', '005-5678901-2', 'diego@email.com', '5555-1005', '1988-06-12', null, $coach1->id],
        ];

        foreach ($clientes as $c) {
            Cliente::create([
                'nombre'           => $c[0],
                'apellido'         => $c[1],
                'cedula'           => $c[2],
                'email'            => $c[3],
                'telefono'         => $c[4],
                'fecha_nacimiento' => $c[5],
                'direccion'        => $c[6],
                'coach_id'         => $c[7],
                'activo'           => true,
            ]);
        }
    }
}