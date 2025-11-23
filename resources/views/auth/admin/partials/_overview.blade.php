<div class="row g-3">
  <div class="col-md-3">
    <div class="card shadow-sm kpi-card"><div class="card-body">
      <div class="label">Usuarios</div>
      <div class="value">{{ $usuariosTotal }}</div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm kpi-card"><div class="card-body">
      <div class="label">Administradores</div>
      <div class="value">{{ $adminsTotal }}</div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm kpi-card"><div class="card-body">
      <div class="label">Puntos totales</div>
      <div class="value">{{ $puntosTotales }}</div>
    </div></div>
  </div>
</div>

<div class="content-box mt-3">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div class="fw-semibold">Canjeos ({{ request('range','week')==='month' ? 'último mes' : (request('range','week')==='all' ? 'histórico' : 'última semana') }})</div>
    <div>
      <a class="btn btn-sm btn-outline-success" href="{{ route('admin.dashboard',['panel'=>'overview','range'=>'week']) }}">Semana</a>
      <a class="btn btn-sm btn-outline-success" href="{{ route('admin.dashboard',['panel'=>'overview','range'=>'month']) }}">Mes</a>
      <a class="btn btn-sm btn-outline-success" href="{{ route('admin.dashboard',['panel'=>'overview','range'=>'all']) }}">Todo</a>
    </div>
  </div>
  <canvas id="chartCanjeos" height="80"></canvas>
</div>

@push('scripts')
<script>
  (function(){
    const el = document.getElementById('chartCanjeos');
    if(!el) return;
    const serie  = @json($serieCanjeos);
    const labels = serie.map(i => i.fecha);
    const data   = serie.map(i => i.total);
    new Chart(el, {
      type: 'line',
      data: { labels, datasets: [{ label:'Canjeos', data, tension:.2 }]},
      options: { plugins:{ legend:{ display:false }}, scales:{ y:{ beginAtZero:true }}}
    });
  })();
</script>
@endpush
