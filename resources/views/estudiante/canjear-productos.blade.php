@extends('auth.estudiante.layout')

@section('title', 'Canjear Productos - Estudiante')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-success text-white shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title mb-1">
                                <i class="fas fa-gift me-2"></i>Canjear Productos
                            </h2>
                            <p class="card-text mb-0">Intercambia tus puntos por productos ecológicos</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="bg-white text-dark rounded-pill px-4 py-3 d-inline-block">
                                <h2 class="mb-0 text-success">{{ number_format($puntosDisponibles, 2) }}</h2>
                                <small class="text-muted">PUNTOS DISPONIBLES</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos que puede canjear -->
    @if(count($productosCanjeables) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>Productos Disponibles para Canjear
                        <span class="badge bg-white text-success ms-2">{{ count($productosCanjeables) }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($productosCanjeables as $producto)
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card h-100 border-success shadow-sm product-card">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                                         class="card-img-top"
                                         alt="{{ $producto->nombreProducto }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <i class="fas fa-gift fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-success">{{ $producto->nombreProducto }}</h5>
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ $producto->descripcion }}
                                    </p>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-coins me-1"></i>
                                                {{ $producto->puntos_valor }} puntos
                                            </span>
                                            <span class="badge bg-primary">
                                                Stock: {{ $producto->stock }}
                                            </span>
                                        </div>
                                        <button class="btn btn-success w-100 btn-canjear"
                                                data-producto-id="{{ $producto->id }}">
                                            <i class="fas fa-shopping-cart me-1"></i>Canjear Ahora
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Productos que NO puede canjear (por falta de puntos) -->
    @if(count($productosNoCanjeables) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-lock me-2"></i>Productos que Requieren Más Puntos
                        <span class="badge bg-white text-warning ms-2">{{ count($productosNoCanjeables) }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($productosNoCanjeables as $producto)
                        <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 border-warning opacity-75">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                                         class="card-img-top"
                                         alt="{{ $producto->nombreProducto }}"
                                         style="height: 150px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 150px;">
                                        <i class="fas fa-gift fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ $producto->nombreProducto }}</h6>
                                    <p class="card-text small text-muted flex-grow-1">
                                        {{ Str::limit($producto->descripcion, 80) }}
                                    </p>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-warning text-dark">
                                                {{ $producto->puntos_valor }} pts
                                            </span>
                                            <span class="badge bg-secondary">
                                                Stock: {{ $producto->stock }}
                                            </span>
                                        </div>
                                        <button class="btn btn-outline-warning btn-sm w-100" disabled>
                                            <i class="fas fa-lock me-1"></i>
                                            Faltan {{ $producto->puntos_valor - $puntosDisponibles }} pts
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Si no hay productos disponibles -->
    @if(count($productosCanjeables) == 0 && count($productosNoCanjeables) == 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-gift fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay productos disponibles</h4>
                    <p class="text-muted">En este momento no hay productos disponibles para canjear.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Historial de canjes recientes -->
    @if($historialCanjes->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-history me-2"></i>Tus Últimos Canjes
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Puntos Canjeados</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historialCanjes as $canje)
                                <tr>
                                    <td>
                                        <strong>{{ $canje->fecha_canjeo->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ $canje->fecha_canjeo->format('H:i') }}</small>
                                    </td>
                                    <td>{{ $canje->producto->nombreProducto }}</td>
                                    <td>
                                        <span class="badge bg-danger fs-6">
                                            -{{ number_format($canje->puntos_totales, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $canje->estado == 'completado' ? 'success' : 'warning' }}">
                                            {{ ucfirst($canje->estado) }}
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
    </div>
    @endif
</div>

<!-- Modal para verificar canjeo -->
<div class="modal fade" id="canjeoModal" tabindex="-1" aria-labelledby="canjeoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="canjeoModalLabel">Confirmar Canjeo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="canjeoModalBody">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="canjeoForm" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Confirmar Canjeo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts directamente en el content -->
<script>
// Función para verificar canjeo
function verificarCanjeo(productoId) {
    console.log('Verificando canjeo para producto:', productoId);

    // Mostrar loading
    $('#canjeoModalBody').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Verificando disponibilidad...</p>
        </div>
    `);

    $('#canjeoModal').modal('show');

    // Hacer petición AJAX
    fetch(`/estudiante/verificar-canjeo/${productoId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta del servidor:', data);
            if (data.success) {
                if (data.data.puede_canjear) {
                    $('#canjeoModalBody').html(`
                        <div class="text-center">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>¡Puedes canjear este producto!</h5>
                            <p><strong>${data.data.producto.nombre}</strong></p>
                            <p class="text-muted">${data.data.producto.descripcion}</p>
                            <div class="alert alert-info">
                                <strong>Puntos requeridos:</strong> ${data.data.puntos_requeridos}<br>
                                <strong>Tus puntos:</strong> ${data.data.puntos_actuales}<br>
                                <strong>Te sobrarán:</strong> ${data.data.diferencia} puntos
                            </div>
                            <small class="text-muted">Stock disponible: ${data.data.producto.stock} unidades</small>
                        </div>
                    `);
                    $('#canjeoForm').attr('action', `/estudiante/procesar-canjeo/${productoId}`);
                    $('#canjeoForm').show();
                } else {
                    $('#canjeoModalBody').html(`
                        <div class="text-center">
                            <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                            <h5>No tienes puntos suficientes</h5>
                            <p>Necesitas ${data.data.puntos_requeridos} puntos pero solo tienes ${data.data.puntos_actuales}</p>
                            <p class="text-warning">Te faltan ${Math.abs(data.data.diferencia)} puntos</p>
                        </div>
                    `);
                    $('#canjeoForm').hide();
                }
            } else {
                $('#canjeoModalBody').html(`
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>Error</h5>
                        <p>${data.message}</p>
                    </div>
                `);
                $('#canjeoForm').hide();
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            $('#canjeoModalBody').html(`
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5>Error de conexión</h5>
                    <p>No se pudo verificar el canjeo: ${error.message}</p>
                </div>
            `);
            $('#canjeoForm').hide();
        });
}

// Event listener para los botones de canjeo
document.addEventListener('DOMContentLoaded', function() {
    // Asignar event listeners a los botones de canjeo
    const botonesCanjeo = document.querySelectorAll('.btn-canjear');
    botonesCanjeo.forEach(boton => {
        boton.addEventListener('click', function() {
            const productoId = this.getAttribute('data-producto-id');
            console.log('Botón clickeado, producto ID:', productoId);
            verificarCanjeo(productoId);
        });
    });

    // Cerrar modal al enviar formulario
    document.getElementById('canjeoForm')?.addEventListener('submit', function(e) {
        $('#canjeoModal').modal('hide');
    });

    console.log('Event listeners configurados correctamente');
});
</script>
@endsection
