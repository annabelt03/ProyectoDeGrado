@extends('auth.partials.layout')
@section('title','Iniciar Sesión - EcoRecicla PET')

@section('content')
<div class="card shadow border-0" style="max-width:420px;margin:0 auto">
  <div class="card-body p-4 text-center">
    <!-- <h4 class="mb-4">
      <i class="fas fa-recycle me-2 text-success"></i>
      Iniciar Sesión
    </h4>-->

    <form method="POST" action="{{ route('login.attempt') }}">@csrf
      <div class="mb-3 text-start">
        <label class="form-label fw-semibold"> Ingrese su Código RFID (8 caracteres)</label>
        <input
          type="text"
          name="numeroRFID"
          class="form-control text-uppercase text-center"
          maxlength="8"
          minlength="8"
          pattern="[0-9A-Fa-f]{8}"
          placeholder="Ej: 239E4CF5"
          required
          autofocus
          value="{{ old('numeroRFID') }}"
        >
        <div class="form-text">Solo letras (A–F) y números (0–9), exactamente 8 caracteres.</div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
      @endif

      <button type="submit" class="btn btn-light w-100">
        Iniciar sesión
      </button>
      <br><br>
      <a href="{{ route('loginAdmin') }}" class="btn btn-outline-light w-100 mt-2">
        Sesión Admin
      </a>
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
  background-color: #9b6767 !important;
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
</style>
@endsection