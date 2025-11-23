@extends('auth.admin.layout')

@section('header', 'Usuarios '.($role ? "($role)" : ''))
@section('content')
<div class="content-box">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div>
      <a href="{{ route('admin.usuarios.index', ['role'=>'usuario']) }}" class="btn btn-sm btn-outline-success">Usuarios</a>
      <a href="{{ route('admin.usuarios.index', ['role'=>'administrador']) }}" class="btn btn-sm btn-outline-success">Administradores</a>
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-sm btn-outline-secondary">Todos</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover">
      <thead class="table-success">
        <tr><th>#</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Puntos</th></tr>
      </thead>
      <tbody>
      @foreach($usuarios as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->nombre }} {{ $u->primerApellido }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ $u->role }}</td>
          <td>{{ $u->puntos }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  {{ $usuarios->withQueryString()->links() }}
</div>
@endsection
