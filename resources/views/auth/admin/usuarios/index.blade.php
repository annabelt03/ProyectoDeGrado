<!-- resources/views/auth/admin/usuarios/index.blade.php  vista administrador -->

@extends('auth.admin.layout')

@section('title','Usuarios - EcoRecicla')
@section('header')
    <div>
        <h1 class="fw-bold text-success mb-1">Inicio</h1>
        <p class="fs-5 text-muted mb-0">
            Bienvenido al panel de administración de
            <strong>EcoRecicla PET</strong>
        </p>
    </div>
@endsection



@section('content')
<div class="content-box">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gestión de Usuarios</h3>
    <div class="d-flex gap-2">
<a href="{{ route('admin.usuarios.create') }}" class="btn btn-success btn-sm">
    + Nuevo Usuario
</a>


</div>

  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-success">
      @if($role === 'usuario')
        <tr>
          <th>ID</th>
          <th>Nombre completo</th>
          <th>Código RFID</th>
          <th>Puntos</th>
          <th class="text-end">Acciones</th>
        </tr>
      @elseif($role === 'administrador')
        <tr>
          <th>ID</th>
          <th>Nombre completo</th>
          <th>Email</th>
          <th class="text-end">Acciones</th>
        </tr>
      @else
        {{-- Si no hay role, mostramos un super set básico --}}
        <tr>
          <th>ID</th>
          <th>Nombre completo</th>
          <th>Rol</th>
          <th>Email</th>
          <th>Código RFID</th>
          <th>Puntos</th>
          <th class="text-end">Acciones</th>
        </tr>
      @endif
      </thead>

      <tbody>
      @forelse($usuarios as $u)
        @php
          $nombreCompleto = trim($u->nombre.' '.$u->primerApellido.' '.($u->segundoApellido ?? ''));
        @endphp

        @if($role === 'usuario')
          <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $nombreCompleto }}</td>
            <td>{{ $u->numeroRFID ?? '—' }}</td>
            <td>{{ $u->puntos ?? 0 }}</td>
            <td class="text-end">
              <a href="{{ route('admin.usuarios.show', $u) }}" class="btn btn-sm btn-info" title="Ver">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="{{ route('admin.usuarios.edit', $u) }}" class="btn btn-sm btn-warning" title="Editar">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>
              <form action="{{ route('admin.usuarios.destroy', $u) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar a {{ $nombreCompleto }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" title="Eliminar">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>

        @elseif($role === 'administrador')
          <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $nombreCompleto }}</td>
            <td>{{ $u->email }}</td>
            <td class="text-end">
              <a href="{{ route('admin.usuarios.show', $u) }}" class="btn btn-sm btn-info" title="Ver">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="{{ route('admin.usuarios.edit', $u) }}" class="btn btn-sm btn-warning" title="Editar">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>
              <form action="{{ route('admin.usuarios.destroy', $u) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar a {{ $nombreCompleto }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" title="Eliminar">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>

        @else
          {{-- listado "Todos" --}}
          <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $nombreCompleto }}</td>
            <td><span class="badge bg-info text-dark">{{ $u->role }}</span></td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->numeroRFID ?? '—' }}</td>
            <td>{{ $u->puntos ?? 0 }}</td>
            <td class="text-end">
              <a href="{{ route('admin.usuarios.show', $u) }}" class="btn btn-sm btn-info" title="Ver">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="{{ route('admin.usuarios.edit', $u) }}" class="btn btn-sm btn-warning" title="Editar">
                <i class="fa-solid fa-pen-to-square"></i>
              </a>
              <form action="{{ route('admin.usuarios.destroy', $u) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar a {{ $nombreCompleto }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" title="Eliminar">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @endif

      @empty
        <tr>
          <td colspan="{{ $role==='usuario' ? 5 : ($role==='administrador' ? 4 : 7) }}" class="text-center text-muted py-3">
            No hay usuarios
          </td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{ $usuarios->withQueryString()->links() }}
</div>
@endsection
