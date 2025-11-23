{{-- resources/views/admin/dashboard.blade.php --}}
@extends('auth.admin.layout')

@section('title', 'Dashboard - Administrador')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Administrador</h1>
        <a href="{{ route('admin.usuarios.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-user-plus fa-sm text-white-50"></i> Crear Usuario
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row">
        <!-- Total Usuarios -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsuarios ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estudiantes Activos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Estudiantes Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estudiantesActivos ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Administradores -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Administradores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdministradores ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuarios Inactivos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Usuarios Inactivos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usuariosInactivos ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-users"></i> Gestionar Usuarios
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-user-plus"></i> Nuevo Usuario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Sistema</h6>
                </div>
                <div class="card-body">
                    <p><strong>Rol:</strong> Administrador</p>
                    <p><strong>Usuario:</strong> {{ auth()->user()->nombre }} {{ auth()->user()->primerApellido }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>RFID:</strong> {{ auth()->user()->numeroRFID }}</p>
                    <p><strong>Estado:</strong>
                        <span class="badge badge-success">{{ auth()->user()->estado }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Usuarios Registrados -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Usuarios Recientes</h6>
                </div>
                <div class="card-body">
                    @if(isset($usuariosRecientes) && $usuariosRecientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email/RFID</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosRecientes as $usuario)
                                <tr>
                                    <td>{{ $usuario->nombre }} {{ $usuario->primerApellido }}</td>
                                    <td>{{ $usuario->email ?: $usuario->numeroRFID }}</td>
                                    <td>
                                        <span class="badge {{ $usuario->role == 'administrador' ? 'badge-primary' : 'badge-info' }}">
                                            {{ $usuario->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $usuario->estado == 'activo' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $usuario->estado }}
                                        </span>
                                    </td>
                                    <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No hay usuarios registrados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
