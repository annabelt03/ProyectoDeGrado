@extends('auth.admin.layout')

@section('title', 'Lista de Productos')
@section('header', 'Lista de Productos')

@section('actions')
    <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Producto
    </a>
@endsection

@section('content')
<!-- Filtros -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.productos.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="{{ request('nombre') }}" placeholder="Buscar por nombre...">
                </div>
                <div class="col-md-2">
                    <label for="puntos_min" class="form-label">Puntos Mín.</label>
                    <input type="number" class="form-control" id="puntos_min" name="puntos_min" 
                           value="{{ request('puntos_min') }}" min="0">
                </div>
                <div class="col-md-2">
                    <label for="puntos_max" class="form-label">Puntos Máx.</label>
                    <input type="number" class="form-control" id="puntos_max" name="puntos_max" 
                           value="{{ request('puntos_max') }}" min="0">
                </div>
                <div class="col-md-2">
                    <label for="stock_min" class="form-label">Stock Mín.</label>
                    <input type="number" class="form-control" id="stock_min" name="stock_min" 
                           value="{{ request('stock_min') }}" min="0">
                </div>
                <div class="col-md-2">
                    <label for="activo" class="form-label">Estado</label>
                    <select class="form-select" id="activo" name="activo">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de productos -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Productos ({{ $productos->total() }})</h5>
        <a href="{{ route('admin.productos.create') }}" class="btn btn-black btn-sm">
            <i ></i>+ Nuevo Producto 
        </a>
    </div>
    <div class="card-body">
        @if($productos->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
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
                            <tr>
                                <td>
                                    @if($producto->imagen)
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                             alt="{{ $producto->nombreProducto }}" 
                                             class="producto-img img-thumbnail " style="width: 30px">
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image fa-2x"></i>
                                            <br><small>Sin imagen</small>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $producto->nombreProducto }}</td>
                                <td>{{ Str::limit($producto->descripcion, 50) }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $producto->puntos_valor }}</span>
                                </td>
                                <td>
                                    @if($producto->stock <= 5)
                                        <span class="badge bg-danger badge-stock">{{ $producto->stock }}</span>
                                    @elseif($producto->stock <= 10)
                                        <span class="badge bg-warning badge-stock">{{ $producto->stock }}</span>
                                    @else
                                        <span class="badge bg-success badge-stock">{{ $producto->stock }}</span>
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
                                        <form action="{{ route('admin.productos.toggle-status', $producto->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $producto->activo ? 'secondary' : 'success' }} btn-sm" 
                                                    title="{{ $producto->activo ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas fa-{{ $producto->activo ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.productos.destroy', $producto->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                    Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }} de {{ $productos->total() }} resultados
                </div>
                {{ $productos->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h4>No se encontraron productos</h4>
                <p class="text-muted">No hay productos que coincidan con los criterios de búsqueda.</p>
                <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Primer Producto
                </a>
            </div>
        @endif
    </div>
</div>
@endsection