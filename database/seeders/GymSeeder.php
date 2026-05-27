<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\MembresiaCliente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // <-- AGREGAR ESTA LÍNEA

class GymSeeder extends Seeder
{
    public function run(): void
    {
        // Desactivar revisión de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar tablas (ahora sí funcionará sin errores)
        VentaDetalle::truncate();
        Venta::truncate();
        MembresiaCliente::truncate();
        Cliente::truncate();
        Producto::truncate();
        Membresia::truncate();
        User::truncate();

        // Reactivar revisión de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==================== 1. USUARIOS ====================
        $admin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@gym.com',
            'password' => Hash::make('12345678'),
            'role'     => 'admin',
            'telefono' => '5555-0000',
            'activo'   => true,
        ]);

        $recepcionista = User::create([
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

        // ==================== 2. MEMBRESÍAS ====================
        $membresias = [
            ['Diaria', 1, 5.00, 'Acceso por un día'],
            ['Semanal', 7, 25.00, 'Acceso por una semana completa'],
            ['Mensual', 30, 80.00, 'Acceso por un mes, la más popular'],
            ['Trimestral', 90, 200.00, 'Acceso por tres meses con descuento'],
            ['Anual', 365, 600.00, 'Acceso por un año, el mejor precio'],
        ];

        foreach ($membresias as $m) {
            Membresia::create([
                'nombre'        => $m[0],
                'duracion_dias' => $m[1],
                'precio'        => $m[2],
                'descripcion'   => $m[3],
                'activa'        => true,
            ]);
        }

        // ==================== 3. PRODUCTOS ====================
        $productosData = [
            ['Proteína Whey Gold 2lbs', 'Suplemento de proteína de suero de leche.', 45.00, 25, 'suplemento'],
            ['Creatina Monohidrato 500g', 'Creatina pura de alta calidad.', 30.00, 15, 'suplemento'],
            ['BCAA Powder 400g', 'Aminoácidos ramificados sabor frutas.', 28.00, 12, 'suplemento'],
            ['Mancuernas Ajustables 20kg', 'Par de mancuernas con sistema rápido.', 120.00, 5, 'equipamiento'],
            ['Cuerda para Saltar Pro', 'Cuerda de velocidad con rodamientos.', 15.00, 20, 'accesorio'],
            ['Guantes de Gimnasio', 'Guantes con muñequera para pesas.', 18.00, 30, 'accesorio'],
            ['Camiseta DryFit Gym', 'Camiseta con tecnología de secado rápido.', 22.00, 0, 'ropa'],
            ['Bandas de Resistencia Set', 'Set de 5 bandas con diferentes niveles.', 25.00, 18, 'accesorio'],
        ];

        foreach ($productosData as $p) {
            Producto::create([
                'nombre'      => $p[0],
                'descripcion' => $p[1],
                'precio'      => $p[2],
                'stock'       => $p[3],
                'categoria'   => $p[4],
                'activo'      => true,
            ]);
        }

        // ==================== 4. CLIENTES (50 TOTALES) ====================
        $nombres = ['Juan', 'Laura', 'Pedro', 'Sofía', 'Diego', 'Mariana', 'Carlos', 'Valentina', 'Roberto', 'Camila',
                    'Mateo', 'Isabella', 'Sebastian', 'Luciana', 'Daniel', 'Victoria', 'Santiago', 'Valeria', 'Nicolas', 'Martina',
                    'Tomas', 'Antonella', 'Pablo', 'Elena', 'Emiliano', 'Luana', 'Hector', 'Florencia', 'Ian', 'Jazmin',
                    'Federico', 'Agustina', 'Bruno', 'Sol', 'Facundo', 'Tatiana', 'Joaquin', 'Melany', 'Lautaro', 'Abril',
                    'Maximiliano', 'Constanza', 'Gaston', 'Jimena', 'Leonardo', 'Cecilia', 'Rafael', 'Lara', 'Matias', 'Teresa'];

        $clientes = [];
        for ($i = 0; $i < 50; $i++) {
            $clientes[] = [
                'nombre'           => $nombres[$i],
                'apellido'         => 'Apellido' . ($i + 1),
                'cedula'           => str_pad($i + 1, 3, '0', STR_PAD_LEFT) . '-' . rand(10000000, 99999999) . '-' . rand(0, 9),
                'email'            => strtolower($nombres[$i]) . ($i+1) . '@email.com',
                'telefono'         => '5555-' . str_pad(rand(1000, 9999), 4, '0'),
                'fecha_nacimiento' => date('Y-m-d', rand(strtotime('1985-01-01'), strtotime('2005-12-31'))),
                'coach_id'         => ($i % 2 == 0) ? $coach1->id : $coach2->id, // Alterna entre los 2 coaches
                'activo'           => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }
        Cliente::insert($clientes); // Inserción masiva (más rápido)

        // ==================== 5. MEMBRESÍAS VENDIDAS (Historial aleatorio) ====================
        $clientesDB = Cliente::all();
        foreach ($clientesDB as $cliente) {
            // Al 70% de los clientes le asignamos una membresía activa o historial
            if (rand(1, 100) <= 70) {
                $membresia = Membresia::inRandomOrder()->first();
                $diasRelativos = rand(-60, 60); // Fechas aleatorias en el último tiempo
                
                MembresiaCliente::create([
                    'cliente_id'    => $cliente->id,
                    'membresia_id'  => $membresia->id,
                    'vendido_por'   => $recepcionista->id,
                    'fecha_inicio'  => now()->addDays($diasRelativos),
                    'fecha_fin'     => now()->addDays($diasRelativos + $membresia->duracion_dias),
                    'precio_pagado' => $membresia->precio,
                    'estado'        => ($diasRelativos + $membresia->duracion_dias > 0) ? 'activa' : 'expirada',
                ]);

                // Registrar la venta en la tabla general de ventas para el balance
                Venta::create([
                    'cliente_id'  => $cliente->id,
                    'usuario_id'  => $recepcionista->id,
                    'tipo'        => 'membresia',
                    'total'       => $membresia->precio,
                    'metodo_pago' => ['efectivo', 'tarjeta', 'transferencia'][array_rand(['efectivo', 'tarjeta', 'transferencia'])],
                    'notas'       => 'Membresía ' . $membresia->nombre,
                ]);
            }
        }

        // ==================== 6. VENTAS DE PRODUCTOS (Para el Balance) ====================
        $metodos = ['efectivo', 'tarjeta', 'transferencia'];
        for ($i = 0; $i < 20; $i++) {
            $total = 0;
            $detallesVenta = [];
            
            // Cada venta tiene entre 1 y 3 productos aleatorios
            $cantidadProductos = rand(1, 3);
            for ($j = 0; $j < $cantidadProductos; $j++) {
                $producto = Producto::inRandomOrder()->where('stock', '>', 0)->first();
                if ($producto) {
                    $cant = rand(1, 2);
                    $subtotal = $producto->precio * $cant;
                    $total += $subtotal;
                    
                    $detallesVenta[] = [
                        'producto_id'     => $producto->id,
                        'cantidad'        => $cant,
                        'precio_unitario' => $producto->precio,
                        'subtotal'        => $subtotal,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];

                    // Descontar stock
                    $producto->decrement('stock', $cant);
                }
            }

            if ($total > 0) {
                $venta = Venta::create([
                    'cliente_id'  => $clientesDB->random()->id,
                    'usuario_id'  => $recepcionista->id,
                    'tipo'        => 'producto',
                    'total'       => $total,
                    'metodo_pago' => $metodos[array_rand($metodos)],
                    'notas'       => 'Venta de productos',
                ]);

                // Insertar detalles
                foreach ($detallesVenta as $detalle) {
                    $detalle['venta_id'] = $venta->id;
                    VentaDetalle::create($detalle);
                }
            }
        }
    }
}