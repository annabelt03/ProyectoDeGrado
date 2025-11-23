@extends('auth.estudiante.layout')

@section('title', 'Detalle de Canje')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('estudiante.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('estudiante.mis-canjes') }}">Mis Canjes</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Detalle del Canje</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información del Producto</h5>
                            <div class="mb-4">
                                @if($canje->producto->imagen)
                                    <img src="{{ asset('storage/' . $canje->producto->imagen) }}" 
                                         alt="{{ $canje->producto->nombreProducto }}" 
                                         class="img-fluid rounded mb-3">
                                @else
                                    <div class="text-center py-4 border rounded bg-light mb-3">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <p class="mt-2 text-muted">Sin imagen</p>
                                    </div>
                                @endif
                                <h6>{{ $canje->producto->nombreProducto }}</h6>
                                <p class="text-muted">{{ $canje->producto->descripcion }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Detalles del Canje</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Fecha de Canje:</th>
                                    <td>{{ $canje->fecha_canjeo->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Cantidad:</th>
                                    <td>{{ $canje->cantidad }} unidad(es)</td>
                                </tr>
                                <tr>
                                    <th>Puntos por unidad:</th>
                                    <td>{{ $canje->producto->puntos_valor }} puntos</td>
                                </tr>
                                <tr>
                                    <th>Puntos totales:</th>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $canje->puntos_totales }} puntos</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <span class="badge bg-{{ $canje->estado == 'entregado' ? 'success' : ($canje->estado == 'cancelado' ? 'danger' : 'warning') }} fs-6">
                                            {{ ucfirst($canje->estado) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>ID de Transacción:</th>
                                    <td><code>#{{ str_pad($canje->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                </tr>
                            </table>

                            @if($canje->estado == 'canjeado')
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Tu producto está pendiente de entrega. Será entregado pronto.
                                </div>
                            @elseif($canje->estado == 'entregado')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i>
                                    ¡Producto entregado exitosamente!
                                </div>
                            @elseif($canje->estado == 'cancelado')
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Este canje ha sido cancelado.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('estudiante.mis-canjes') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver a Mis Canjes
                                </a>
                                <a href="{{ route('estudiante.productos') }}" class="btn btn-primary">
                                    <i class="fas fa-gift"></i> Realizar otro canje
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection