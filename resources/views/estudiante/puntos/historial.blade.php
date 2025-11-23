@extends('auth.estudiante.layout')

@section('title', 'Historial de Puntos')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <h2 class="text-2xl font-bold">Historial de Puntos</h2>
        <p class="text-gray-600">Registro de todos los puntos obtenidos por reciclaje</p>
    </div>

    <div class="p-6">
        <!-- Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm text-blue-600">Puntos Totales</p>
                <p class="text-2xl font-bold">{{ $usuario->puntos }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-sm text-green-600">Total Registros</p>
                <p class="text-2xl font-bold">{{ $registros->total() }}</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg">
                <p class="text-sm text-yellow-600">Puntos Obtenidos</p>
                <p class="text-2xl font-bold">{{ number_format($registros->sum('puntos_asignados'), 2) }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <p class="text-sm text-purple-600">Material Reciclado</p>
                <p class="text-2xl font-bold">{{ number_format($registros->sum('peso_gramos') / 1000, 1) }} kg</p>
            </div>
        </div>

        <!-- Tabla de Historial -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peso (g)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Puntos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($registros as $registro)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $registro->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($registro->peso_gramos) }} g
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-green-600 font-semibold">+{{ number_format($registro->puntos_asignados, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Acreditado</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No hay registros de puntos aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $registros->links() }}
        </div>
    </div>
</div>
@endsection
