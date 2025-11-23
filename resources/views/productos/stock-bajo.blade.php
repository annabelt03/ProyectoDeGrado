@extends('auth.admin.layout')

@section('title', 'Stock Bajo')
@section('header', 'Productos con Stock Bajo')

@section('actions')
    <a href="{{ route('admin.productos.index') }}" class="btn btn-primary">
        <i class="fas fa-boxes"></i> Todos los Productos
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle text-warning"></i> 
            Productos con Stock Bajo ({{ $productos->total() }})
        </h5>
    </div>
    <div class="card-body">
        @if($productos->count() > 0)
            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i> 
                Se muestran productos con stock menor o igual a <strong>{{ $umbral }}</strong> unidades.
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-warning">
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Puntos</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr class="{{ $producto->stock == 0 ? 'table-danger' : '' }}">
                                <td>
                                    @if($producto->imagen)
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                             alt="{{ $producto->nombreProducto }}" 
                                             class="producto-img img-thumbnail" style="max-width: 80px;">
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $producto->nombreProducto }}</td>
                                <td>{{ Str::limit($producto->descripcion, 30) }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $producto->puntos_valor }}</span>
                                </td>
                                <td>
                                    @if($producto->stock == 0)
                                        <span class="badge bg-danger fs-6">AGOTADO</span>
                                    @else
                                        <span class="badge bg-warning fs-6">{{ $producto->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($producto->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.productos.show', $producto->id) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.productos.edit', $producto->id) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.productos.index', ['stock_min' => $producto->stock + 1]) }}" 
                                           class="btn btn-success btn-sm" title="Ajustar Stock">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }} de {{ $productos->total() }} productos
                </div>
                {{ $productos->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h4>¡Excelente!</h4>
                <p class="text-muted">No hay productos con stock bajo. Todos los productos tienen stock suficiente.</p>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-primary">
                    <i class="fas fa-boxes"></i> Ver Todos los Productos
                </a>
            </div>
        @endif
    </div>
</div>
@endsection