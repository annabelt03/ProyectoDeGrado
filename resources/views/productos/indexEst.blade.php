@extends('auth.estudiante.layout') {{-- Ajusta según tu layout para estudiantes --}}

@section('title', 'Catálogo de Productos')
@section('header', 'Catálogo de Productos')

@section('content')
<!-- Filtros simplificados -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('estudiante.productos.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Buscar producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="{{ request('nombre') }}" placeholder="Buscar por nombre...">
                </div>
                <div class="col-md-3">
                    <label for="puntos_max" class="form-label">Puntos máximos</label>
                    <input type="number" class="form-control" id="puntos_max" name="puntos_max" 
                           value="{{ request('puntos_max') }}" min="0" placeholder="Puntos disponibles">
                </div>
                <div class="col-md-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria" name="categoria">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Información del estudiante -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-coins"></i> Mis Puntos</h5>
                <h2 class="mb-0">{{ auth()->user()->puntos ?? 0 }} puntos</h2>
                <small>Disponibles para canjear</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-gift"></i> Productos Disponibles</h5>
                <h2 class="mb-0">{{ $productos->total() }} productos</h2>
                <small>Listos para canjear</small>
            </div>
        </div>
    </div>
</div>

<!-- Grid de productos -->
<div class="row">
    @forelse($productos as $producto)
        <div class="col-md-4 mb-4">
            <div class="card h-100 producto-card {{ $producto->stock == 0 ? 'card-outstock' : '' }}">
                @if($producto->stock == 0)
                    <div class="sold-out-overlay">
                        <span class="sold-out-text">AGOTADO</span>
                    </div>
                @endif
                
                <div class="card-img-top-container">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                             alt="{{ $producto->nombreProducto }}" 
                             class="card-img-top producto-imagen">
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-image fa-3x"></i>
                            <p class="mt-2">Sin imagen</p>
                        </div>
                    @endif
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $producto->nombreProducto }}</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        {{ Str::limit($producto->descripcion, 100) }}
                    </p>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary fs-6">
                                <i class="fas fa-coins"></i> {{ $producto->puntos_valor }} puntos
                            </span>
                            <span class="badge bg-{{ $producto->stock > 5 ? 'success' : ($producto->stock > 0 ? 'warning' : 'danger') }}">
                                Stock: {{ $producto->stock }}
                            </span>
                        </div>
                        
                        @if($producto->stock > 0)
                            @if(auth()->user()->puntos >= $producto->puntos_valor)
                                <button type="button" class="btn btn-success w-100 btn-canjear" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalCanjear"
                                        data-producto-id="{{ $producto->id }}"
                                        data-producto-nombre="{{ $producto->nombreProducto }}"
                                        data-producto-puntos="{{ $producto->puntos_valor }}">
                                    <i class="fas fa-exchange-alt"></i> Canjear Ahora
                                </button>
                            @else
                                <button class="btn btn-secondary w-100" disabled 
                                        title="No tienes suficientes puntos">
                                    <i class="fas fa-lock"></i> Puntos insuficientes
                                </button>
                            @endif
                        @else
                            <button class="btn btn-outline-secondary w-100" disabled>
                                <i class="fas fa-times"></i> Agotado
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                <h4>No hay productos disponibles</h4>
                <p class="text-muted">No se encontraron productos que coincidan con tu búsqueda.</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Paginación -->
@if($productos->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $productos->links() }}
    </div>
@endif

<!-- Modal para confirmar canje -->
<div class="modal fade" id="modalCanjear" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Canje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres canjear <strong id="productoNombre"></strong>?</p>
                <p>Coste: <span id="productoPuntos" class="badge bg-primary fs-6"></span> puntos</p>
                <p>Tus puntos disponibles: <span class="badge bg-success">{{ auth()->user()->puntos ?? 0 }}</span> puntos</p>
                <p class="text-muted small">Después del canje, tus puntos se reducirán y el producto será reservado para ti.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formCanjear" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-exchange-alt"></i> Confirmar Canje
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .producto-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .producto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .producto-imagen {
        height: 200px;
        object-fit: cover;
    }
    .card-img-top-container {
        height: 200px;
        overflow: hidden;
        background: #f8f9fa;
    }
    .card-outstock {
        opacity: 0.7;
        position: relative;
    }
    .sold-out-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    .sold-out-text {
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        transform: rotate(-15deg);
    }
    .btn-canjear {
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalCanjear = document.getElementById('modalCanjear');
        
        modalCanjear.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productoId = button.getAttribute('data-producto-id');
            const productoNombre = button.getAttribute('data-producto-nombre');
            const productoPuntos = button.getAttribute('data-producto-puntos');
            
            document.getElementById('productoNombre').textContent = productoNombre;
            document.getElementById('productoPuntos').textContent = productoPuntos + ' puntos';
            
            // Actualizar el formulario con la ruta correcta
            const form = document.getElementById('formCanjear');
            form.action = '{{ route("estudiante.productos.canjear", "") }}/' + productoId;
        });
    });
</script>
@endpush