@extends('auth.admin.layout')

@section('title', 'Estadísticas de Puntos')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Estadísticas de Puntos</h2>
                <p class="text-gray-600">Análisis y reportes del sistema de puntos</p>
            </div>
            <a href="{{ route('admin.puntos.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Registros
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Top Usuarios -->
        <div class="mb-8">
            <h3 class="text-xl font-bold mb-4">Top 10 Usuarios con Más Puntos</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Puntos Acumulados</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Reciclado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topUsuarios as $index => $usuario)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-bold">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-500 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $usuario->nombre }} {{ $usuario->primerApellido }}</div>
                                        <div class="text-sm text-gray-500">{{ $usuario->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-yellow-600 font-bold text-lg">{{ number_format($usuario->puntos) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $totalReciclado = $usuario->registrosPuntos()->sum('peso_gramos');
                                @endphp
                                <span class="text-green-600 font-semibold">{{ number_format($totalReciclado / 1000, 1) }} kg</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Puntos por Día -->
        <div class="mb-8">
            <h3 class="text-xl font-bold mb-4">Puntos por Día (Últimos 30 días)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Puntos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registros</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Promedio por Registro</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($puntosPorDia as $punto)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($punto->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-green-600 font-semibold">{{ number_format($punto->total_puntos, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($punto->total_registros) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($punto->total_puntos / max($punto->total_registros, 1), 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Distribución de Pesos -->
        <div>
            <h3 class="text-xl font-bold mb-4">Distribución por Rango de Peso</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rango de Peso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Registros</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Promedio de Puntos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($distribucionPesos as $distribucion)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $distribucion->rango_peso }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($distribucion->total) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($distribucion->promedio_puntos, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $porcentaje = ($distribucion->total / $totalRegistros) * 100;
                                @endphp
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                    {{ number_format($porcentaje, 1) }}%
                                </span>
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
