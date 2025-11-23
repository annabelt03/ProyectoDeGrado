<div class="row g-3">
  <div class="col-md-7">
    <div class="card shadow-sm">
      <div class="card-header fw-semibold">Top 5 productos más canjeados ({{ $range==='month' ? 'mes' : ($range==='all' ? 'todo' : 'semana') }})</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="table-success">
            <tr><th>#</th><th>Producto</th><th>Total</th></tr>
          </thead>
          <tbody>
          @forelse($topProductos as $i=>$p)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $p->producto->nombreProducto ?? '—' }}</td>
              <td>{{ $p->total }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted py-3">Sin datos</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
