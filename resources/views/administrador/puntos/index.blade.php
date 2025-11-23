@extends('auth.admin.layout')

@section('title', 'Gestión de Puntos')

@section('content')
<div class="card shadow-sm">
    <div class="card-header border-bottom bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1">Gestión de Puntos</h2>
                <p class="text-muted mb-0">Todos los registros de puntos del sistema</p>
            </div>
            <a href="{{ route('admin.puntos.estadisticas') }}" class="btn btn-primary">
                <i class="fas fa-chart-bar me-2"></i>Ver Estadísticas
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card-body border-bottom bg-light">
        <form action="{{ route('admin.puntos.index') }}" method="GET" class="row g-3">
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
                        <p class="card-text small">Total Puntos Asignados</p>
                        <h3 class="card-title">{{ number_format($totalPuntos, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <p class="card-text small">Promedio por Registro</p>
                        <h3 class="card-title">{{ number_format($promedioPuntos, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <p class="card-text small">Total Registros</p>
                        <h3 class="card-title">{{ number_format($totalRegistros) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Registros -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Peso (g)</th>
                        <th scope="col">Puntos</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $registro)
                    <tr>
                        <td class="text-muted">#{{ $registro->id }}</td>
                        <td>
                            @if($registro->usuario)
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $registro->usuario->nombre }}</div>
                                    <div class="text-muted small">{{ $registro->usuario->numeroRFID }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-muted">Anónimo</span>
                            @endif
                        </td>
                        <td>
                            {{ number_format($registro->peso_gramos) }}g
                        </td>
                        <td>
                            <span class="text-success fw-semibold">+{{ number_format($registro->puntos_asignados, 2) }}</span>
                        </td>
                        <td class="text-muted">
                            {{ $registro->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.puntos.show', $registro->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.puntos.destroy', $registro->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No hay registros de puntos.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $registros->links() }}
        </div>
    </div>
</div>
@endsection
