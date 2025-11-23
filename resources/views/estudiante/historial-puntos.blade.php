@extends('auth.estudiante.layout')

@section('title', 'Historial de Puntos - Estudiante')

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
                                <i class="fas fa-history me-2"></i>Historial de Puntos
                            </h2>
                            <p class="mb-0 text-muted">Todos tus registros de puntos obtenidos</p>
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
                                Total Puntos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($historialPuntos->sum('puntos_asignados'), 2) }}
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Registros</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $historialPuntos->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
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
                                Promedio por Registro</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($historialPuntos->avg('puntos_asignados') ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                Máximo Puntos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($historialPuntos->max('puntos_asignados') ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
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
                <i class="fas fa-table me-2"></i>Registros de Puntos
            </h6>
            <span class="badge bg-primary">Página {{ $historialPuntos->currentPage() }} de {{ $historialPuntos->lastPage() }}</span>
        </div>
        <div class="card-body">
            @if($historialPuntos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Peso (gramos)</th>
                                <th>Puntos Asignados</th>
                                <th>ID Mensaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historialPuntos as $registro)
                            <tr>
                                <td>
                                    <strong>{{ $registro->created_at->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $registro->created_at->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ number_format($registro->peso_gramos) }} g
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success fs-6">
                                        +{{ number_format($registro->puntos_asignados, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted font-monospace">
                                        {{ Str::limit($registro->msg_id, 10) }}
                                    </small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip"
                                            title="Ver detalles"
                                            onclick="verDetalle({{ $registro }})">
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
                        Mostrando {{ $historialPuntos->firstItem() }} - {{ $historialPuntos->lastItem() }} de {{ $historialPuntos->total() }} registros
                    </div>
                    <nav>
                        {{ $historialPuntos->links() }}
                    </nav>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay registros de puntos</h4>
                    <p class="text-muted">Aún no tienes registros de puntos en tu historial.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para detalles -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleModalLabel">Detalles del Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleModalBody">
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
function verDetalle(registro) {
    const fecha = new Date(registro.created_at).toLocaleString('es-ES');
    const leidoEn = registro.leido_en ? new Date(registro.leido_en).toLocaleString('es-ES') : 'No especificado';

    $('#detalleModalBody').html(`
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <strong>Fecha de Registro:</strong><br>
                    ${fecha}
                </div>
                <div class="mb-3">
                    <strong>Peso:</strong><br>
                    <span class="badge bg-secondary fs-6">${registro.peso_gramos.toLocaleString()} gramos</span>
                </div>
                <div class="mb-3">
                    <strong>Puntos Asignados:</strong><br>
                    <span class="badge bg-success fs-6">+${registro.puntos_asignados.toFixed(2)} puntos</span>
                </div>
                <div class="mb-3">
                    <strong>Leído en:</strong><br>
                    ${leidoEn}
                </div>
                <div class="mb-3">
                    <strong>ID Mensaje:</strong><br>
                    <code class="text-muted">${registro.msg_id}</code>
                </div>
                ${registro.numeroRFID ? `
                <div class="mb-3">
                    <strong>RFID:</strong><br>
                    <code>${registro.numeroRFID}</code>
                </div>
                ` : ''}
            </div>
        </div>
    `);

    $('#detalleModal').modal('show');
}

// Inicializar tooltips
$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection
