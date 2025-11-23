@extends('auth.estudiante.layout')

@section('title', 'Historial de Canjes - Estudiante')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="h4 mb-1">
                                <i class="fas fa-exchange-alt me-2"></i>Historial de Canjes
                            </h2>
                            <p class="mb-0 text-muted">Todos los productos que has canjeado</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="btn-group">
                                <a href="{{ route('estudiante.dashboard') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Canjes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $historialCanjes->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Puntos Canjeados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($historialCanjes->sum('puntos_totales'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
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
                                Productos Diferentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $historialCanjes->pluck('producto_id')->unique()->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-2x text-gray-300"></i>
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
                                Último Canje</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($historialCanjes->count() > 0)
                                    {{ $historialCanjes->first()->fecha_canjeo->diffForHumans() }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de historial -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Registros de Canjes
            </h6>
            <span class="badge bg-primary">Página {{ $historialCanjes->currentPage() }} de {{ $historialCanjes->lastPage() }}</span>
        </div>
        <div class="card-body">
            @if($historialCanjes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Puntos Canjeados</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historialCanjes as $canje)
                            <tr>
                                <td>
                                    <strong>{{ $canje->fecha_canjeo->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $canje->fecha_canjeo->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($canje->producto->imagen)
                                            <img src="{{ asset('storage/' . $canje->producto->imagen) }}"
                                                 class="rounded me-3"
                                                 alt="{{ $canje->producto->nombreProducto }}"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center me-3"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-gift text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $canje->producto->nombreProducto }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($canje->producto->descripcion, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary fs-6">{{ $canje->cantidad }}</span>
                                </td>
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
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip"
                                            title="Ver detalles"
                                            onclick="verDetalleCanje({{ $canje }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Mostrando {{ $historialCanjes->firstItem() }} - {{ $historialCanjes->lastItem() }} de {{ $historialCanjes->total() }} canjes
                    </div>
                    <nav>
                        {{ $historialCanjes->links() }}
                    </nav>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay historial de canjes</h4>
                    <p class="text-muted">Aún no has canjeado ningún producto.</p>
                    <a href="{{ route('estudiante.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-gift me-1"></i>Ver Productos Disponibles
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para detalles del canje -->
<div class="modal fade" id="detalleCanjeModal" tabindex="-1" aria-labelledby="detalleCanjeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleCanjeModalLabel">Detalles del Canje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleCanjeModalBody">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function verDetalleCanje(canje) {
    const fecha = new Date(canje.fecha_canjeo).toLocaleString('es-ES');

    $('#detalleCanjeModalBody').html(`
        <div class="row">
            <div class="col-md-4 text-center">
                ${canje.producto.imagen ?
                    `<img src="{{ asset('storage/') }}/${canje.producto.imagen}"
                         class="img-fluid rounded"
                         alt="${canje.producto.nombreProducto}"
                         style="max-height: 200px;">` :
                    `<div class="bg-light rounded d-flex align-items-center justify-content-center"
                         style="height: 200px;">
                        <i class="fas fa-gift fa-3x text-muted"></i>
                     </div>`
                }
            </div>
            <div class="col-md-8">
                <h4>${canje.producto.nombreProducto}</h4>
                <p class="text-muted">${canje.producto.descripcion}</p>

                <div class="row mt-4">
                    <div class="col-6">
                        <strong>Fecha de Canje:</strong><br>
                        ${fecha}
                    </div>
                    <div class="col-6">
                        <strong>Cantidad:</strong><br>
                        <span class="badge bg-primary fs-6">${canje.cantidad} unidad(es)</span>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-6">
                        <strong>Puntos Canjeados:</strong><br>
                        <span class="badge bg-danger fs-6">-${parseFloat(canje.puntos_totales).toFixed(2)} puntos</span>
                    </div>
                    <div class="col-6">
                        <strong>Estado:</strong><br>
                        <span class="badge bg-${canje.estado == 'completado' ? 'success' : 'warning'}">${canje.estado.charAt(0).toUpperCase() + canje.estado.slice(1)}</span>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <strong>Información del Producto:</strong><br>
                        <small class="text-muted">
                            Valor original: ${canje.producto.puntos_valor} puntos por unidad
                        </small>
                    </div>
                </div>
            </div>
        </div>
    `);

    $('#detalleCanjeModal').modal('show');
}

// Inicializar tooltips
$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection
