@extends('auth.admin.layout')

@section('title', 'Dashboard Admin - EcoRecicla')
@section('header', 'Dashboard Administrador')

@section('content')

<!-- Filtros rápidos -->
<div class="content-box mb-4">
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <span class="chip">Vista rápida:</span>
        <a href="{{ route('estudiante.dashboard', ['range' => 'week']) }}" class="btn btn-sm btn-outline-success">
            Estadísticas Avanzadas
        </a>
    </div>
</div>

<!-- Estadísticas principales -->
<div class="row">
    <!-- Usuarios -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalUsuarios }}</h4>
                        <p>Total Usuarios</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <div class="small">
                    <span class="badge bg-success">{{ $estudiantesActivos }} activos</span>
                    <span class="badge bg-warning">{{ $usuariosInactivos }} inactivos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos -->
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalProductos }}</h4>
                        <p>Total Productos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
                <div class="small">
                    <span class="badge bg-success">{{ $productosActivos }} activos</span>
                    <span class="badge bg-danger">{{ $productosStockBajo }} stock bajo</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Canjes -->
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalCanjes }}</h4>
                        <p>Total Canjes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
                <div class="small">
                    <span class="badge bg-warning">{{ $canjesHoy }} hoy</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Puntos -->
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $puntosCanjeadosTotal }}</h4>
                        <p>Puntos Canjeados</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-coins fa-2x"></i>
                    </div>
                </div>
                <div class="small">
                    <span class="badge bg-info">{{ $puntosCanjeadosHoy }} hoy</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Últimos Canjes -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Últimos Canjes</h5>
            </div>
            <div class="card-body">
                @if($ultimosCanjes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Producto</th>
                                    <th>Puntos</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosCanjes as $canje)
                                    <tr>
                                        <td>{{ $canje->usuario->nombre }} {{ $canje->usuario->primerApellido }}</td>
                                        <td>{{ $canje->producto->nombreProducto }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $canje->puntos_totales }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $canje->estado == 'entregado' ? 'success' : ($canje->estado == 'cancelado' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($canje->estado) }}
                                            </span>
                                        </td>
                                        <td>{{ $canje->fecha_canjeo->format('d/m H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <p>No hay canjes registrados</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Productos -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Productos Más Populares</h5>
            </div>
            <div class="card-body">
                @if($topProductos->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topProductos as $producto)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $producto->producto->nombreProducto }}</h6>
                                    <small class="text-muted">{{ $producto->total_unidades }} unidades</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $producto->total_canjes }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                        <p>No hay datos de productos</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Estados de Canjes -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Estados de Canjes</h5>
            </div>
            <div class="card-body">
                @foreach($estadosCanjes as $estado)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-capitalize">{{ $estado->estado }}</span>
                        <span class="badge bg-secondary">{{ $estado->total }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Usuarios Recientes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Usuarios Recientes</h5>
            </div>
            <div class="card-body">
                @if($usuariosRecientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Puntos</th>
                                    <th>Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosRecientes as $usuario)
                                    <tr>
                                        <td>{{ $usuario->nombre }} {{ $usuario->primerApellido }}</td>
                                        <td>{{ $usuario->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $usuario->role == 'administrador' ? 'danger' : 'primary' }}">
                                                {{ ucfirst($usuario->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $usuario->estado == 'activo' ? 'success' : 'warning' }}">
                                                {{ ucfirst($usuario->estado) }}
                                            </span>
                                        </td>
                                        <td>{{ $usuario->puntos }}</td>
                                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p>No hay usuarios registrados</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection