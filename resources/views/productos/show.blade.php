@extends('auth.admin.layout')

@section('title', 'Ver Producto')
@section('header', 'Detalles del Producto')

@section('actions')
    <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $producto->nombreProducto }}</h5>
                <div class="btn-group">
                    <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('admin.productos.toggle-status', $producto->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $producto->activo ? 'secondary' : 'success' }} btn-sm">
                            <i class="fas fa-{{ $producto->activo ? 'times' : 'check' }}"></i> 
                            {{ $producto->activo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                 alt="{{ $producto->nombreProducto }}" 
                                 class="img-fluid rounded mb-3">
                        @else
                            <div class="text-muted py-5 border rounded">
                                <i class="fas fa-image fa-3x mb-3"></i>
                                <br>Sin imagen
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Nombre:</th>
                                <td>{{ $producto->nombreProducto }}</td>
                            </tr>
                            <tr>
                                <th>Descripción:</th>
                                <td>{{ $producto->descripcion }}</td>
                            </tr>
                            <tr>
                                <th>Puntos Valor:</th>
                                <td>
                                    <span class="badge bg-primary fs-6">{{ $producto->puntos_valor }} puntos</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Stock:</th>
                                <td>
                                    @if($producto->stock <= 5)
                                        <span class="badge bg-danger fs-6">{{ $producto->stock }} unidades</span>
                                    @elseif($producto->stock <= 10)
                                        <span class="badge bg-warning fs-6">{{ $producto->stock }} unidades</span>
                                    @else
                                        <span class="badge bg-success fs-6">{{ $producto->stock }} unidades</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    @if($producto->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Creado:</th>
                                <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Actualizado:</th>
                                <td>{{ $producto->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST" 
                          onsubmit="return confirm('¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar Producto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection