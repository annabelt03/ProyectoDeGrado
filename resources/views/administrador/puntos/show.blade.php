@extends('auth.admin.layout')

@section('title', 'Detalles del Registro')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Detalles del Registro</h2>
                <p class="text-gray-600">Información completa del registro de puntos</p>
            </div>
            <a href="{{ route('admin.puntos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información del Registro -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Información del Registro</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID del Registro</label>
                        <p class="mt-1 text-sm text-gray-900">#{{ $registro->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Msg ID</label>
                        <p class="mt-1 text-sm text-gray-900 font-mono">{{ $registro->msg_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Peso</label>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($registro->peso_gramos) }} gramos</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Puntos Asignados</label>
                        <p class="mt-1 text-sm text-green-600 font-semibold">{{ number_format($registro->puntos_asignados, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registro->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Leído en</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $registro->leido_en ? $registro->leido_en->format('d/m/Y H:i:s') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información del Usuario -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Información del Usuario</h3>

                @if($registro->usuario)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $registro->usuario->nombre }} {{ $registro->usuario->primerApellido }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">RFID</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $registro->usuario->numeroRFID }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $registro->usuario->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Puntos Actuales</label>
                            <p class="mt-1 text-sm text-yellow-600 font-semibold">{{ $registro->usuario->puntos }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <p class="mt-1 text-sm">
                                <span class="bg-{{ $registro->usuario->estado == 'activo' ? 'green' : 'red' }}-100 text-{{ $registro->usuario->estado == 'activo' ? 'green' : 'red' }}-800 px-2 py-1 rounded text-xs">
                                    {{ ucfirst($registro->usuario->estado) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-yellow-800">No hay usuario asociado a este registro.</p>
                    @if($registro->numeroRFID)
                    <p class="text-sm mt-2">RFID: {{ $registro->numeroRFID }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Acciones -->
        <div class="mt-6 pt-6 border-t">
            <form action="{{ route('admin.puntos.destroy', $registro->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    <i class="fas fa-trash mr-2"></i>Eliminar Registro
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
