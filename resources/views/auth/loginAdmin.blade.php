{{-- resources/views/auth/loginAdmin.blade.php --}}
@extends('auth.partials.layout')
@section('title','Iniciar Sesión Admin - EcoRecicla PET')

@section('content')
<div class="card shadow border-0" style="max-width:420px;margin:0 auto">
  <div class="card-body p-4">
    <h4 class="mb-3"><i class="fas fa-user-shield me-2"></i> Iniciar Sesión (Admin)</h4>

    <form method="POST" action="{{ route('loginAdmin.attempt') }}">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Correo</label>
        <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required autofocus>
        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input id="password" name="password" type="password" class="form-control" required>
        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="remember" name="remember">
        <label class="form-check-label" for="remember">Recordarme</label>
      </div>

      <button type="submit" class="btn btn-light w-100">
        Iniciar sesión
      </button>
    </form>
  </div>
</div>

<style>
:root{
  --primary-green: #4CAF50;   /* Verde reciclaje */
  --dark-green: #2F4F4F;      /* Verde bosque */
  --brown: #8B5E3C;           /* Marrón madera */
  --accent: #A0522D;          /* Marrón rojizo */
  --beige: #F5F5DC;           /* Beige arena */
  --text-dark: #2E2E2E;
  --text-muted: #5C5C5C;
}

/* Estilos de botones adaptados del código original */
.btn-light {
  background-color: #fff !important;
  border-color: #fff !important;
  color: var(--text-dark) !important;
  font-weight: 500;
  border-radius: 20px;
  transition: .3s;
  padding: 10px 20px;
}

.btn-light:hover {
  background-color: rgba(255, 255, 255, 0.8) !important;
  border-color: rgba(255, 255, 255, 0.8) !important;
  color: var(--text-dark) !important;
}

.btn-outline-light {
  background-color: transparent !important;
  border-color: #fff !important;
  color: #fff !important;
  font-weight: 500;
  border-radius: 20px;
  border-width: 2px;
  transition: .3s;
  padding: 10px 20px;
}

.btn-outline-light:hover {
  background-color: #fff !important;
  color: var(--text-dark) !important;
}

/* Estilo para la tarjeta de login */
.card {
  background: rgba(255, 255, 255, 0.9) !important;
  backdrop-filter: blur(4px);
  border-radius: 20px !important;
}

/* Fondo de la página */
body {
  background: linear-gradient(135deg, var(--dark-green), var(--primary-green)) !important;
  background-attachment: fixed !important;
  display: flex;
  align-items: center;
  min-height: 100vh;
}

/* Estilos para los inputs del formulario */
.form-control {
  border-radius: 10px;
  border: 1px solid #ddd;
  padding: 10px 15px;
  transition: border-color 0.3s;
}

.form-control:focus {
  border-color: var(--primary-green);
  box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
}

/* Estilo para el icono del título */
.fa-user-shield {
  color: var(--text-dark);
}
</style>
@endsection