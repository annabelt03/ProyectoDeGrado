@extends('auth.estudiante.layout')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Catálogo de Productos</h2>
                <p class="text-gray-600">Canjea tus puntos por productos exclusivos</p>
            </div>
            <div class="bg-yellow-100 border border-yellow-300 px-4 py-2 rounded-lg">
                <span class="font-semibold">Puntos disponibles: {{ $usuario->puntos }}</span>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($productos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($productos as $producto)
            <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombreProducto }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-gift text-gray-400 text-4xl"></i>
                </div>
                @endif

                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2">{{ $producto->nombreProducto }}</h3>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($producto->descripcion, 100) }}</p>

                    <div class="flex justify-between items-center mb-3">
                        <span class="text-2xl font-bold text-blue-600">{{ $producto->puntos_valor }} pts</span>
                        <span class="text-sm text-gray-500">Stock: {{ $producto->stock }}</span>
                    </div>

                    @if($producto->stock < 1)
                    <button class="w-full bg-gray-400 text-white py-2 rounded cursor-not-allowed" disabled>
                        Agotado
                    </button>
                    @elseif($usuario->puntos < $producto->puntos_valor)
                    <button class="w-full bg-red-400 text-white py-2 rounded cursor-not-allowed" disabled>
                        Puntos Insuficientes
                    </button>
                    @else
                    <form action="{{ route('usuario.canjeo.canjear', $producto->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded transition">
                            Canjear Ahora
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-gift text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500 text-lg">No hay productos disponibles en este momento.</p>
        </div>
        @endif
    </div>
</div>
@endsection
