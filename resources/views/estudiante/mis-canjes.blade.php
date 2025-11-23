@extends('auth.estudiante.layout')

@section('title', 'Mis Canjes')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Mis Canjes</h2>
            <p class="text-muted">Historial de todos tus canjes realizados</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('estudiante.productos') }}" class="btn btn-primary">
                <i class="fas fa-gift"></i> Canjear Productos
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary">{{ $totalPuntosCanjeados }}</h4>
                            <p class="mb-0 text-muted">Total Puntos Canjeados</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">{{ $canjes->total() }}</h4>
                            <p class="mb-0 text-muted">Total Canjes</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">{{ $canjes->where('estado', 'entregado')->count() }}</h4>
                            <p class="mb-0 text-muted">Entregados</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">{{ $canjes->where('estado', 'canjeado')->count() }}</h4>
                            <p class="mb-0 text-muted">Pendientes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('estudiante.mis-canjes') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="">Todos los estados</option>
                            <option value="canjeado" {{ request('estado') == 'canjeado' ? 'selected' : '' }}>Canjeado</option>
                            <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_desde" class="form-label">Fecha desde</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" 
                               value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_hasta" class="form-label">Fecha hasta</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" 
                               value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Canjes -->
    <div class="card">
        <div class="card-body">
            @if($canjes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Fecha</th>
                                <th>Cantidad</th>
                                <th>Puntos</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($canjes as $canje)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($canje->producto->imagen)
                                                <img src="{{ asset('storage/' . $canje->producto->imagen) }}" 
                                                     alt="{{ $canje->producto->nombreProducto }}" 
                                                     class="rounded me-3" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $canje->producto->nombreProducto }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($canje->producto->descripcion, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $canje->fecha_canjeo->format('d/m/Y H:i') }}</td>
                                    <td>{{ $canje->cantidad }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $canje->puntos_totales }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $canje->estado == 'entregado' ? 'success' : ($canje->estado == 'cancelado' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($canje->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('estudiante.ver-canje', $canje->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Mostrando {{ $canjes->firstItem() }} a {{ $canjes->lastItem() }} de {{ $canjes->total() }} canjes
                    </div>
                    {{ $canjes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h4>No hay canjes registrados</h4>
                    <p class="text-muted">Aún no has realizado ningún canje de productos.</p>
                    <a href="{{ route('estudiante.productos') }}" class="btn btn-primary">
                        <i class="fas fa-gift"></i> Realizar primer canje
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection