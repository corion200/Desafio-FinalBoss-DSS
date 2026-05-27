<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GymAdmin') - Sistema de Gimnasio</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    @auth
    <!-- Navbar superior -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h1m16 0h1M5.6 5.6l.7.7m11.4 11.4l.7.7M5.6 18.4l.7-.7m11.4-11.4l.7-.7M9 12h6m-8 0a8 8 0 1116 0 8 8 0 01-16 0z"/>
                        </svg>
                        <span class="text-xl font-bold">GymAdmin</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-300">
                        {{ Auth::user()->name }}
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full
                            @if(Auth::user()->role === 'admin') bg-red-500
                            @elseif(Auth::user()->role === 'recepcionista') bg-blue-500
                            @else bg-green-500
                            @endif">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white text-sm">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex gap-6">
            <!-- Sidebar -->
            <aside class="w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menú</p>
                    </div>
                    <nav class="p-2">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                📊 Dashboard
                            </a>
                            <a href="{{ route('clientes.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('clientes.*') ? 'bg-green-100 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                👥 Clientes
                            </a>
                            <a href="{{ route('admin.productos.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('admin.productos.*') ? 'bg-green-100 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                📦 Productos
                            </a>
                            <a href="{{ route('ventas.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('ventas.*') ? 'bg-green-100 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                🛒 Ventas
                            </a>
                            <a href="{{ route('admin.membresias.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('admin.membresias.*') ? 'bg-green-100 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                🎫 Membresías
                            </a>
                            <a href="{{ route('admin.balance.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('admin.balance.*') ? 'bg-green-100 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                💰 Balance
                            </a>
                            <a href="{{ route('admin.reportes.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('admin.reportes.*') ? 'bg-green-100 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                🔧 Reportes Equipo
                            </a>
                        @elseif(Auth::user()->role === 'recepcionista')
                            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                📊 Dashboard
                            </a>
                            <a href="{{ route('clientes.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('clientes.*') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                👥 Clientes
                            </a>
                            <a href="{{ route('ventas.create') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('ventas.create') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                🛒 Nueva Venta
                            </a>
                            <a href="{{ route('ventas.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ (request()->routeIs('ventas.index') || request()->routeIs('ventas.show')) ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                📋 Historial Ventas
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('dashboard') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                📊 Dashboard
                            </a>
                            <a href="{{ route('coach.clientes.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('coach.clientes.*') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                👥 Mis Clientes
                            </a>
                            <a href="{{ route('coach.reportes.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md mb-1 {{ request()->routeIs('coach.reportes.*') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                                🔧 Mis Reportes
                            </a>
                        @endif
                    </nav>
                </div>
            </aside>

            <!-- Contenido principal -->
            <main class="flex-1 min-w-0">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold text-xl leading-none">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex justify-between items-center">
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-red-700 font-bold text-xl leading-none">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @else
    @yield('content')
    @endauth

    <!-- CONTENEDOR DE JAVASCRIPTS DINÁMICOS -->
    @stack('scripts')
</body>
</html>