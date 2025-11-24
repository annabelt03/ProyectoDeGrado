@extends('auth.admin.layout')

@section('title', 'Estadísticas de Puntos')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Estadísticas de Puntos</h1>
    <p class="text-gray-600">Análisis de puntos asignados a usuarios</p>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <form method="GET" class="flex gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Tarjetas de resumen -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Puntos Asignados</p>
        <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticasPuntos['totalPuntosAsignados'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Registros</p>
        <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticasPuntos['totalRegistros']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Promedio por Registro</p>
        <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticasPuntos['promedioPuntosPorRegistro'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Peso Total (kg)</p>
        <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticasPuntos['pesoTotal'], 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top usuarios -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Top 10 Usuarios por Puntos</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Usuario</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Puntos Totales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topUsuarios as $usuario)
                    <tr class="border-t">
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $usuario->nombre }} {{ $usuario->primerApellido }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ number_format($usuario->puntos_totales, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gráfico de puntos por día -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Puntos por Día (Últimos 30 días)</h3>
        <canvas id="puntosDiaChart" width="400" height="300"></canvas>
    </div>
</div>

@section('scripts')
<script>
    const puntosDiaData = @json($puntosPorDia);

    const ctx = document.getElementById('puntosDiaChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: puntosDiaData.map(item => item.fecha),
            datasets: [{
                label: 'Puntos Asignados',
                data: puntosDiaData.map(item => item.total_puntos),
                backgroundColor: 'rgb(34, 197, 94)',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
@endsection
