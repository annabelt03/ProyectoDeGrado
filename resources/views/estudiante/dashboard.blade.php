@extends('auth.estudiante.layout')

@section('title', 'Dashboard - Estudiante')

@section('content')
<div class="container-fluid py-4">
    <!-- Header con información del estudiante -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title mb-1">¡Hola, {{ $estudiante->nombre }} {{ $estudiante->primerApellido }}!</h2>
                            <p class="card-text mb-0">Bienvenido a tu dashboard de puntos ecológicos</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-inline-block bg-white text-dark rounded-pill px-4 py-2">
                                <h3 class="mb-0 text-primary">{{ number_format($puntosTotales, 2) }} puntos</h3>
                                <small class="text-muted">Puntos totales acumulados</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Puntos Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['puntos_hoy'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Registros Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['registros_hoy'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-recycle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Registros</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['total_registros'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Canjes Realizados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $historialCanjes->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna izquierda: Últimos registros y productos -->
        <div class="col-lg-8">
            <!-- Últimos registros de puntos -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Últimos Registros de Puntos
                    </h6>
                    <a href="{{ route('estudiante.historial-puntos') }}" class="btn btn-sm btn-outline-primary">
                        Ver Todo
                    </a>
                </div>
                <div class="card-body">
                    @if($ultimosRegistros->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Peso (gramos)</th>
                                        <th>Puntos Asignados</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ultimosRegistros as $registro)
                                    <tr>
                                        <td>{{ $registro->created_at->format('d/m/Y') }}</td>
                                        <td>{{ number_format($registro->peso_gramos) }} g</td>
                                        <td>
                                            <span class="badge bg-success">
                                                +{{ number_format($registro->puntos_asignados, 2) }}
                                            </span>
                                        </td>
                                        <td>{{ $registro->created_at->format('H:i:s') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay registros de puntos aún</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Productos disponibles -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-gift me-2"></i>Productos Disponibles para Canjear
                    </h6>
                </div>
                <div class="card-body">
                    @if($productosDisponibles->count() > 0)
                        <div class="row">
                            @foreach($productosDisponibles as $producto)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 product-card">
                                    @if($producto->imagen)
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombreProducto }}" style="height: 150px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                            <i class="fas fa-gift fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">{{ $producto->nombreProducto }}</h6>
                                        <p class="card-text small text-muted flex-grow-1">
                                            {{ Str::limit($producto->descripcion, 80) }}
                                        </p>
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-coins me-1"></i>
                                                    {{ $producto->puntos_valor }} puntos
                                                </span>
                                                <span class="badge bg-secondary">
                                                    Stock: {{ $producto->stock }}
                                                </span>
                                            </div>
                                            @if($puntosTotales >= $producto->puntos_valor)
                                                <button class="btn btn-success btn-sm w-100"
                                                        onclick="verificarCanjeo({{ $producto->id }})">
                                                    <i class="fas fa-shopping-cart me-1"></i>Canjear
                                                </button>
                                            @else
                                                <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                                    <i class="fas fa-lock me-1"></i>
                                                    Faltan {{ $producto->puntos_valor - $puntosTotales }} puntos
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay productos disponibles en este momento</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna derecha: Historial de canjes y información -->
        <div class="col-lg-4">
            <!-- Historial de canjes recientes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exchange-alt me-2"></i>Últimos Canjes
                    </h6>
                    <a href="{{ route('estudiante.historial-canjes') }}" class="btn btn-sm btn-outline-primary">
                        Ver Todo
                    </a>
                </div>
                <div class="card-body">
                    @if($historialCanjes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($historialCanjes as $canje)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $canje->producto->nombreProducto }}</h6>
                                        <small class="text-muted">
                                            {{ $canje->fecha_canjeo->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-danger">
                                        -{{ $canje->puntos_totales }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-exchange-alt fa-2x text-muted mb-2"></i>
                            <p class="text-muted small">No has realizado canjes aún</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información rápida -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información del Estudiante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nombre completo:</strong><br>
                        {{ $estudiante->nombre }} {{ $estudiante->primerApellido }}
                        @if($estudiante->segundoApellido)
                            {{ $estudiante->segundoApellido }}
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        {{ $estudiante->email ?? 'No especificado' }}
                    </div>
                    <div class="mb-3">
                        <strong>RFID:</strong><br>
                        {{ $estudiante->numeroRFID }}
                    </div>
                    <div class="mb-3">
                        <strong>Estado:</strong><br>
                        <span class="badge bg-{{ $estudiante->estado == 'activo' ? 'success' : ($estudiante->estado == 'inactivo' ? 'warning' : 'danger') }}">
                            {{ ucfirst($estudiante->estado) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection

@section('scripts')
<script>
function verificarCanjeo(productoId) {
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
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data.puede_canjear) {
                    $('#canjeoModalBody').html(`
                        <div class="text-center">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>¡Puedes canjear este producto!</h5>
                            <p><strong>${data.data.producto.nombre}</strong></p>
                            <p>${data.data.producto.descripcion}</p>
                            <div class="alert alert-info">
                                <strong>Puntos requeridos:</strong> ${data.data.puntos_requeridos}<br>
                                <strong>Tus puntos:</strong> ${data.data.puntos_actuales}<br>
                                <strong>Te sobrarán:</strong> ${data.data.diferencia} puntos
                            </div>
                        </div>
                    `);
                    $('#canjeoForm').attr('action', `/estudiante/canjear-producto/${productoId}`);
                } else {
                    $('#canjeoModalBody').html(`
                        <div class="text-center">
                            <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                            <h5>No tienes puntos suficientes</h5>
                            <p>Necesitas ${data.data.puntos_requeridos} puntos pero solo tienes ${data.data.puntos_actuales}</p>
                            <p>Te faltan ${Math.abs(data.data.diferencia)} puntos</p>
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
            $('#canjeoModalBody').html(`
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5>Error de conexión</h5>
                    <p>No se pudo verificar el canjeo</p>
                </div>
            `);
            $('#canjeoForm').hide();
        });
}

// Cerrar modal al enviar formulario
$('#canjeoForm').on('submit', function(e) {
    $('#canjeoModal').modal('hide');
});
</script>
@endsection
