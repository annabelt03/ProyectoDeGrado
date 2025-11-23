@extends('auth.admin.layout')

@section('title', 'Estadísticas de Canjeos')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Estadísticas de Canjeos</h2>
                <p class="text-gray-600">Análisis y reportes del sistema de canjeos</p>
            </div>
            <a href="{{ route('admin.canjeos.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Canjeos
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Productos Más Populares -->
        <div class="mb-8">
            <h3 class="text-xl font-bold mb-4">Productos Más Canjeados</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Canjeos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Puntos Totales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor en Puntos</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($productosPopulares as $index => $producto)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-bold">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($producto->producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->producto->imagen) }}" alt="{{ $producto->producto->nombreProducto }}" class="w-10 h-10 object-cover rounded mr-3">
                                    @else
                                    <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                        <i class="fas fa-gift text-gray-400"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="font-medium">{{ $producto->producto->nombreProducto }}</div>
                                        <div class="text-sm text-gray-500">Stock: {{ $producto->producto->stock }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-lg">{{ number_format($producto->total_canjeos) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-red-600 font-semibold">{{ number_format($producto->total_puntos) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-yellow-600 font-semibold">{{ number_format($producto->producto->puntos_valor) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Canjeos por Día -->
        <div>
            <h3 class="text-xl font-bold mb-4">Canjeos por Día (Últimos 30 días)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Canjeos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Puntos Canjeados</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Promedio por Canjeo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($canjeosPorDia as $canjeo)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($canjeo->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold">{{ number_format($canjeo->total_canjeos) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-red-600 font-semibold">{{ number_format($canjeo->total_puntos) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($canjeo->total_puntos / max($canjeo->total_canjeos, 1), 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
