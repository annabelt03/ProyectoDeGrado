@extends('auth.estudiante.layout')

@section('title', 'Productos Disponibles')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Productos Disponibles</h2>
            <p class="text-muted">Tus puntos disponibles: <strong>{{ $usuario->puntos }}</strong></p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('estudiante.mis-canjes') }}" class="btn btn-outline-primary">
                <i class="fas fa-history"></i> Mis Canjes
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('estudiante.productos') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="nombre" class="form-label">Buscar producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="{{ request('nombre') }}" placeholder="Nombre del producto...">
                    </div>
                    <div class="col-md-3">
                        <label for="puntos_max" class="form-label">Puntos máximos</label>
                        <input type="number" class="form-control" id="puntos_max" name="puntos_max" 
                               value="{{ request('puntos_max', $usuario->puntos) }}" min="0" max="{{ $usuario->puntos }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('estudiante.productos') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Productos -->
    <div class="row">
        @if($productos->count() > 0)
            @foreach($productos as $producto)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                 class="card-img-top" 
                                 alt="{{ $producto->nombreProducto }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="text-center py-5 bg-light">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="mt-2 text-muted">Sin imagen</p>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $producto->nombreProducto }}</h5>
                            <p class="card-text text-muted">{{ $producto->descripcion }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-primary mb-0">{{ $producto->puntos_valor }} puntos</span>
                                <span class="badge bg-{{ $producto->stock > 5 ? 'success' : ($producto->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $producto->stock }} disponibles
                                </span>
                            </div>
                            @if($producto->puntos_valor <= $usuario->puntos)
                                <div class="d-grid gap-2">
                                    <a href="{{ route('estudiante.ver-producto', $producto->id) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-shopping-cart"></i> Canjear Ahora
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning text-center py-2">
                                    <small>Necesitas {{ $producto->puntos_valor - $usuario->puntos }} puntos más</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No se encontraron productos</h4>
                    <p class="text-muted">No hay productos disponibles con los filtros aplicados.</p>
                    <a href="{{ route('estudiante.productos') }}" class="btn btn-primary">
                        Ver todos los productos
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Paginación -->
    @if($productos->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection