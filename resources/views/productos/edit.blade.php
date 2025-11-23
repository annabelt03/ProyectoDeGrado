@extends('auth.admin.layout')

@section('title', 'Editar Producto')
@section('header', 'Editar Producto: ' . $producto->nombreProducto)

@section('actions')
    <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editar Información del Producto</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombreProducto" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control @error('nombreProducto') is-invalid @enderror" 
                                   id="nombreProducto" name="nombreProducto" 
                                   value="{{ old('nombreProducto', $producto->nombreProducto) }}" required>
                            @error('nombreProducto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="puntos_valor" class="form-label">Puntos Valor *</label>
                            <input type="number" class="form-control @error('puntos_valor') is-invalid @enderror" 
                                   id="puntos_valor" name="puntos_valor" 
                                   value="{{ old('puntos_valor', $producto->puntos_valor) }}" min="0" required>
                            @error('puntos_valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción *</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="stock" class="form-label">Stock *</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', $producto->stock) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                   id="imagen" name="imagen" accept="image/*">
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            @if($producto->imagen)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                         alt="Imagen actual" class="img-thumbnail" style="max-height: 100px;">
                                    <div class="form-text">Imagen actual</div>
                                </div>
                            @endif
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" 
                                       {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    Producto activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Producto
                        </button>
                        <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection