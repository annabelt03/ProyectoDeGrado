@extends('auth.estudiante.layout')

@section('title', 'Historial de Canjeos')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <h2 class="text-2xl font-bold">Historial de Canjeos</h2>
        <p class="text-gray-600">Todos tus canjeos realizados</p>
    </div>

    <div class="p-6">
        @if($canjeos->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Puntos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($canjeos as $canjeo)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($canjeo->producto->imagen)
                                <img src="{{ asset('storage/' . $canjeo->producto->imagen) }}" alt="{{ $canjeo->producto->nombreProducto }}" class="w-10 h-10 object-cover rounded">
                                @else
                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-gift text-gray-400"></i>
                                </div>
                                @endif
                                <div class="ml-4">
                                    <div class="font-medium">{{ $canjeo->producto->nombreProducto }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $canjeo->fecha_canjeo->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-red-600 font-semibold">-{{ $canjeo->puntos_totales }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($canjeo->estado == 'canjeado')
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Canjeado</span>
                            @elseif($canjeo->estado == 'entregado')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Entregado</span>
                            @else
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Cancelado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($canjeo->estado == 'canjeado' && $canjeo->created_at->diffInHours(now()) <= 1)
                            <form action="{{ route('usuario.canjeo.cancelar', $canjeo->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('¿Estás seguro de que quieres cancelar este canjeo?')">
                                    Cancelar
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400 text-sm">No disponible</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $canjeos->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-receipt text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500 text-lg">No has realizado ningún canjeo aún.</p>
            <a href="{{ route('usuario.canjeo.catalogo') }}" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Ir al Catálogo
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
