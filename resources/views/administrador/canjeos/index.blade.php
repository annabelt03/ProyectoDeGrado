@extends('auth.admin.layout')

@section('title', 'Gestión de Canjeos')

@section('content')
<div class="card shadow-sm">
    <div class="card-header border-bottom bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1">Gestión de Canjeos</h2>
                <p class="text-muted mb-0">Todos los canjeos realizados en el sistema</p>
            </div>
            <a href="{{ route('admin.canjeos.estadisticas') }}" class="btn btn-primary">
                <i class="fas fa-chart-bar me-2"></i>Ver Estadísticas
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card-body border-bottom bg-light">
        <form action="{{ route('admin.canjeos.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Usuario</label>
                <select name="usuario_id" class="form-select">
                    <option value="">Todos los usuarios</option>
                    @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ request('usuario_id') == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->nombre }} {{ $usuario->primerApellido }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    @foreach($estados as $estado)
                    <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                        {{ ucfirst($estado) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Estadísticas -->
    <div class="card-body border-bottom">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <p class="card-text small">Total Canjeos</p>
                        <h3 class="card-title">{{ number_format($totalCanjeos) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <p class="card-text small">Pendientes de Entrega</p>
                        <h3 class="card-title">{{ number_format($canjeosPendientes) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <p class="card-text small">Puntos Canjeados</p>
                        <h3 class="card-title">{{ number_format($puntosCanjeados) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Canjeos -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Producto</th>
                        <th scope="col">Puntos</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($canjeos as $canjeo)
                    <tr>
                        <td class="text-muted">#{{ $canjeo->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $canjeo->usuario->nombre }}</div>
                                    <div class="text-muted small">{{ $canjeo->usuario->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($canjeo->producto->imagen)
                                <img src="{{ asset('storage/' . $canjeo->producto->imagen) }}" alt="{{ $canjeo->producto->nombreProducto }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-gift text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-medium">{{ $canjeo->producto->nombreProducto }}</div>
                                    <div class="text-muted small">Cantidad: {{ $canjeo->cantidad }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-danger fw-semibold">-{{ $canjeo->puntos_totales }}</span>
                        </td>
                        <td class="text-muted">
                            {{ $canjeo->fecha_canjeo->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            @if($canjeo->estado == 'canjeado')
                            <span class="badge bg-warning text-dark">Canjeado</span>
                            @elseif($canjeo->estado == 'entregado')
                            <span class="badge bg-success">Entregado</span>
                            @else
                            <span class="badge bg-danger">Cancelado</span>
                            @endif
                        </td>
                        <td>
                            @if($canjeo->estado == 'canjeado')
                            <form action="{{ route('admin.canjeos.entregado', $canjeo->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success me-1" title="Marcar como entregado">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.canjeos.cancelar', $canjeo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de cancelar este canjeo?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancelar canjeo">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-muted">No hay acciones</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay canjeos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $canjeos->links() }}
        </div>
    </div>
</div>
@endsection
