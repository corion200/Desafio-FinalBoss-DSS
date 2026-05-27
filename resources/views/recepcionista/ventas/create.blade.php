@extends('layouts.app')

@section('title', 'Nueva Venta')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Registrar Venta de Productos</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulario -->
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('ventas.store') }}" id="ventaForm">
            @csrf
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-5 mb-6">
                <h2 class="font-semibold mb-4">Datos de la Venta</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cliente (Opcional)</label>
                        <select name="cliente_id" class="w-full px-3 py-2 border rounded-lg text-sm">
                            <option value="">-- Cliente general --</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombreCompleto() }} ({{ $cliente->cedula }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Método de Pago *</label>
                        <select name="metodo_pago" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                            <option value="">Seleccionar...</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                    <input type="text" name="notas" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Nota opcional de la venta">
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-5 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold">Productos</h2>
                    <button type="button" onclick="agregarProducto()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition">+ Agregar Producto</button>
                </div>

                <div id="contenedor-productos" class="space-y-4">
                    <!-- Fila inicial -->
                    <div class="p-4 bg-gray-50 rounded-lg border producto-fila">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                            <div class="md:col-span-6">
                                <label class="block text-xs text-gray-500 mb-1">Producto</label>
                                <select name="productos[0][id]" onchange="actualizarPrecio(this)" class="w-full px-3 py-2 border rounded-lg text-sm producto-select" required>
                                    <option value="">Seleccionar producto...</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}" data-stock="{{ $producto->stock }}">
                                            {{ $producto->nombre }} (Stock: {{ $producto->stock }}) - ${{ number_format($producto->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Cantidad</label>
                                <input type="number" name="productos[0][cantidad]" value="1" min="1" onchange="calcularSubtotal(this)" class="w-full px-3 py-2 border rounded-lg text-sm cantidad-input" required>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs text-gray-500 mb-1">Subtotal</label>
                                <input type="text" readonly class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 subtotal-input" value="$0.00">
                            </div>
                            <div class="md:col-span-1">
                                <button type="button" onclick="eliminarProducto(this)" class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2 rounded-lg text-sm transition">X</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold text-lg transition">
                Procesar Venta
            </button>
        </form>
    </div>

    <!-- Resumen de Venta -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-5 sticky top-6">
            <h2 class="font-semibold mb-4 border-b pb-2">Resumen de Venta</h2>
            <div class="space-y-2 text-sm text-gray-600 mb-4" id="resumen-productos">
                <p class="text-gray-400 italic">No hay productos agregados</p>
            </div>
            <div class="border-t pt-3 mt-3">
                <div class="flex justify-between text-xl font-bold text-gray-800">
                    <span>TOTAL:</span>
                    <span id="total-venta">$0.00</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let pIndex = 1;
    const productosData = @json($productos);

    function agregarProducto() {
        const contenedor = document.getElementById('contenedor-productos');
        let optionsHtml = '<option value="">Seleccionar producto...</option>';
        productosData.forEach(p => {
            optionsHtml += `<option value="${p.id}" data-precio="${p.precio}" data-stock="${p.stock}">${p.nombre} (Stock: ${p.stock}) - $${parseFloat(p.precio).toFixed(2)}</option>`;
        });

        const html = `
        <div class="p-4 bg-gray-50 rounded-lg border producto-fila">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-6">
                    <label class="block text-xs text-gray-500 mb-1">Producto</label>
                    <select name="productos[${pIndex}][id]" onchange="actualizarPrecio(this)" class="w-full px-3 py-2 border rounded-lg text-sm producto-select" required>
                        ${optionsHtml}
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs text-gray-500 mb-1">Cantidad</label>
                    <input type="number" name="productos[${pIndex}][cantidad]" value="1" min="1" onchange="calcularSubtotal(this)" class="w-full px-3 py-2 border rounded-lg text-sm cantidad-input" required>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs text-gray-500 mb-1">Subtotal</label>
                    <input type="text" readonly class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-100 subtotal-input" value="$0.00">
                </div>
                <div class="md:col-span-1">
                    <button type="button" onclick="eliminarProducto(this)" class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2 rounded-lg text-sm transition">X</button>
                </div>
            </div>
        </div>`;
        contenedor.insertAdjacentHTML('beforeend', html);
        pIndex++;
    }

    function eliminarProducto(btn) {
        btn.closest('.producto-fila').remove();
        calcularTotalGeneral();
    }

    function actualizarPrecio(select) {
        calcularSubtotal(select.closest('.producto-fila').querySelector('.cantidad-input'));
    }

    function calcularSubtotal(input) {
        const fila = input.closest('.producto-fila');
        const select = fila.querySelector('.producto-select');
        const subtotalInput = fila.querySelector('.subtotal-input');
        
        const option = select.options[select.selectedIndex];
        const precio = parseFloat(option.dataset.precio) || 0;
        const cantidad = parseInt(input.value) || 0;
        
        const subtotal = precio * cantidad;
        subtotalInput.value = `$${subtotal.toFixed(2)}`;
        
        calcularTotalGeneral();
    }

    function calcularTotalGeneral() {
        const filas = document.querySelectorAll('.producto-fila');
        let total = 0;
        let resumenHtml = '';
        let hayProductos = false;

        filas.forEach(fila => {
            const select = fila.querySelector('.producto-select');
            const cantidadInput = fila.querySelector('.cantidad-input');
            const option = select.options[select.selectedIndex];
            
            if(select.value) {
                hayProductos = true;
                const nombre = option.text.split('(Stock:')[0].trim();
                const precio = parseFloat(option.dataset.precio) || 0;
                const cantidad = parseInt(cantidadInput.value) || 0;
                const subtotal = precio * cantidad;
                
                total += subtotal;
                resumenHtml += `<div class="flex justify-between"><span>${nombre} x${cantidad}</span><span>$${subtotal.toFixed(2)}</span></div>`;
            }
        });

        document.getElementById('resumen-productos').innerHTML = hayProductos ? resumenHtml : '<p class="text-gray-400 italic">No hay productos agregados</p>';
        document.getElementById('total-venta').innerText = `$${total.toFixed(2)}`;
    }
</script>
@endpush