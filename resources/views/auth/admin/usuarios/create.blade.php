@extends('auth.admin.layout')
@section('content')
    <div class="card">
    <div class="card-body">
        <h4>Crear Usuario</h4>
        <form method="POST" action="{{ route('admin.usuarios.store') }}">@csrf
        <div class="row g-3">
            <div class="col-md-6">
            <label class="form-label">Nombre *</label>
            <input name="nombre" class="form-control" required value="{{ old('nombre') }}">
            </div>
            <div class="col-md-6">
            <label class="form-label">Primer Apellido *</label>
            <input name="primerApellido" class="form-control" required value="{{ old('primerApellido') }}">
            </div>
            <div class="col-md-6">
            <label class="form-label">Segundo Apellido</label>
            <input name="segundoApellido" class="form-control" value="{{ old('segundoApellido') }}">
            </div>

            <!-- Campo RFID OBLIGATORIO -->
            <div class="col-md-6">
            <label class="form-label">Número RFID *</label>
            <input type="text" name="numeroRFID" class="form-control" required value="{{ old('numeroRFID') }}">
            @error('numeroRFID')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            </div>

            <div class="col-md-6">
            <label class="form-label">Fecha Nacimiento</label>
            <input type="date" name="fechaNacimiento" class="form-control" value="{{ old('fechaNacimiento') }}">
            </div>
            <div class="col-md-6">
            <label class="form-label">Género</label>
            <select name="genero" class="form-select">
                <option value="">Seleccionar...</option>
                <option value="m" @selected(old('genero')==='m')>Masculino</option>
                <option value="f" @selected(old('genero')==='f')>Femenino</option>
            </select>
            </div>

            <!-- Campos condicionales para administrador -->
            <div id="campos-admin">
                <div class="col-md-6">
                <label class="form-label">Email * <small>(solo para admin)</small></label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                <label class="form-label">Password * <small>(solo para admin)</small></label>
                <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="col-md-6">
                <label class="form-label">Confirmar Password * <small>(solo para admin)</small></label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
            <label class="form-label">Rol *</label>
            <select name="role" id="role" class="form-select" required>
                <option value="estudiante" @selected(old('role')==='estudiante')>Estudiante</option>
                <option value="administrador" @selected(old('role')==='administrador')>Administrador</option>
            </select>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary">Guardar</button>
        </div>
        </form>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const camposAdmin = document.getElementById('campos-admin');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');

            function toggleAdminFields() {
                if (roleSelect.value === 'administrador') {
                    camposAdmin.style.display = 'block';
                    emailInput.required = true;
                    passwordInput.required = true;
                    passwordConfirmationInput.required = true;
                } else {
                    camposAdmin.style.display = 'none';
                    emailInput.required = false;
                    passwordInput.required = false;
                    passwordConfirmationInput.required = false;
                    emailInput.value = '';
                    passwordInput.value = '';
                    passwordConfirmationInput.value = '';
                }
            }

            // Ejecutar al cargar la página
            toggleAdminFields();

            // Ejecutar cuando cambie el rol
            roleSelect.addEventListener('change', toggleAdminFields);
        });
    </script>

    <style>
        #campos-admin {
            display: none;
            width: 100%;
        }
        #campos-admin .col-md-6 {
            display: block;
        }
    </style>
@endsection
