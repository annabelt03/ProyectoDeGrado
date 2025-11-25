<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>@yield('title','EcoRecicla PET — Admin')</title>

  {{-- Bootstrap + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    :root{
      --primary-green: #4CAF50;   /* Verde reciclaje */
      --dark-green: #2F4F4F;      /* Verde bosque */
      --brown: #8B5E3C;           /* Marrón madera */
      --accent: #A0522D;          /* Marrón rojizo */
      --beige: #F5F5DC;           /* Beige arena */
      --text-dark: #2E2E2E;
      --text-muted: #5C5C5C;

      --sidebar-bg: var(--dark-green);
      --sidebar-bg-2: var(--primary-green);
      --brand: #e6fff7;
      --chip: #e9f7f2;
      --title: var(--dark-green);
      --ink: var(--text-dark);
      --muted: var(--text-muted);
      --kpi: var(--primary-green);
    }

    html,body{height:100%}
    body{background:var(--beige);color:var(--ink)}
    .layout{
      display:grid; grid-template-columns: 260px 1fr; min-height:100vh;
    }
    .sidebar{
      background:linear-gradient(180deg,var(--sidebar-bg) 0%, var(--sidebar-bg-2) 100%);
      color:#fff; padding:18px 14px; position:sticky; top:0; height:100vh;
    }
    .brand{display:flex; gap:.6rem; align-items:center; font-weight:700; color:var(--brand); text-decoration:none; font-size:1.1rem; margin-bottom:16px;}
    .brand i{font-size:1.2rem}
    .menu .item{display:flex; align-items:center; gap:.7rem; padding:10px 12px; border-radius:12px; color:#eafaf6; text-decoration:none; margin:4px 0;}
    .menu .item i{width:22px; text-align:center}
    .menu .item:hover{background:rgba(255,255,255,.12)}
    .menu .item.active{background:#e6fff7;color:var(--dark-green)}
    .menu .item.active i{color:var(--dark-green)}

    .sidebar .section-title{font-size:.8rem; letter-spacing:.08em; text-transform:uppercase; opacity:.7; margin:14px 12px 6px}

    .main{
      padding:18px 20px 28px;
    }

    /* Topbar */
    .topbar{
      display:flex; align-items:center; gap:16px; margin-bottom:14px;
    }
    .topbar .title{color:var(--title); margin:0}
    .search{
      flex:1; display:flex; align-items:center; gap:.6rem; background:#fff; border:1px solid #e5efe9; border-radius:16px; padding:8px 12px;
    }
    .search input{border:none; outline:none; width:100%; background:transparent}
    .avatar-btn{display:flex; align-items:center; gap:.6rem; background:#fff; padding:8px 12px; border-radius:16px; border:1px solid #e5efe9;}
    .chip{background:var(--chip); color:var(--title); border-radius:999px; padding:.15rem .6rem; font-size:.78rem}

    /* Cards KPI */
    .kpi-card .label{color:var(--muted); font-size:.9rem}
    .kpi-card .value{font-size:1.6rem; font-weight:800; color:var(--kpi)}

    /* Table actions */
    .action i{font-size:1.05rem}
    .action.view{color:#0ea5e9}
    .action.edit{color:#f59e0b}
    .action.team{color:#8b5cf6}
    .action.key{color:#10b981}
    .action.del{color:#ef4444}

    .content-box{background:#e9f7f2; border:1px solid #e0efe9; border-radius:16px; padding:16px 18px; margin-bottom:14px}

    /* Estilos de botones adaptados del código original */
    .btn-light {
      background-color: #fff !important;
      border-color: #fff !important;
      color: var(--text-dark) !important;
      font-weight: 500;
      border-radius: 20px;
      transition: .3s;
      padding: 8px 16px;
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
      padding: 8px 16px;
    }

    .btn-outline-light:hover {
      background-color: #fff !important;
      color: var(--text-dark) !important;
    }

    /* Botones específicos del admin */
    .btn-success {
      background-color: var(--primary-green) !important;
      border-color: var(--primary-green) !important;
      color: #fff !important;
      font-weight: 500;
      border-radius: 20px;
      transition: .3s;
      padding: 8px 16px;
    }

    .btn-success:hover {
      background-color: var(--dark-green) !important;
      border-color: var(--dark-green) !important;
      color: #fff !important;
    }

    .btn-danger {
      background-color: #ef4444 !important;
      border-color: #ef4444 !important;
      color: #fff !important;
      font-weight: 500;
      border-radius: 20px;
      transition: .3s;
      padding: 8px 16px;
    }

    .btn-danger:hover {
      background-color: #dc2626 !important;
      border-color: #dc2626 !important;
      color: #fff !important;
    }
  </style>
</head>
<body>
<div class="layout">
  {{-- SIDEBAR --}}
  <aside class="sidebar">
  <a href="{{ route('admin.dashboard') }}" class="brand">
    <i class="fa-solid fa-recycle"></i> EcoRecicla PET
  </a>

  <nav class="menu">
    {{-- Inicio --}}
    <a class="item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
      <i class="fa-solid fa-house"></i> Inicio
    </a>

    <div class="section-title">Gestión</div>

    {{-- Usuarios (submenú) --}}
    @php $usuariosOpen = request()->is('admin/usuarios*'); @endphp
    <a class="item d-flex justify-content-between align-items-center {{ $usuariosOpen ? 'active' : '' }}"
       data-bs-toggle="collapse" href="#menuUsuarios" role="button" aria-expanded="{{ $usuariosOpen ? 'true':'false' }}">
      <span><i class="fa-solid fa-users"></i> Usuarios</span>
      <i class="fa-solid fa-chevron-down"></i>
    </a>
    <div class="collapse {{ $usuariosOpen ? 'show' : '' }}" id="menuUsuarios">
      <a class="item ms-4 {{ request('role')==='estudiante' ? 'active' : '' }}"
         href="{{ route('admin.usuarios.index', ['role'=>'estudiante']) }}">
        <i class="fa-regular fa-circle"></i>Niños/adolecentes
      </a>
      <a class="item ms-4 {{ request('role')==='administrador' ? 'active' : '' }}"
         href="{{ route('admin.usuarios.index', ['role'=>'administrador']) }}">
        <i class="fa-regular fa-circle"></i> Administradores
      </a>
      <a class="item ms-4 {{ request('role')===null ? 'active' : '' }}"
     href="{{ route('admin.usuarios.index') }}">
    <i class="fa-regular fa-circle"></i> Todos
  </a>
    </div>

    {{-- Puntos (submenú) --}}
    @php $puntosOpen = request()->routeIs('admin.puntos.*'); @endphp
    <a class="item d-flex justify-content-between align-items-center {{ $puntosOpen ? 'active' : '' }}"
       data-bs-toggle="collapse" href="#menuPuntos" role="button" aria-expanded="{{ $puntosOpen ? 'true':'false' }}">
      <span><i class="fa-solid fa-coins"></i> Puntos</span>
      <i class="fa-solid fa-chevron-down"></i>
    </a>
    <div class="collapse {{ $puntosOpen ? 'show' : '' }}" id="menuPuntos">
      <a class="item ms-4 {{ request()->routeIs('admin.puntos.index') ? 'active' : '' }}"
         href="{{ route('admin.puntos.index') }}">
        <i class="fa-regular fa-circle"></i> Todos los registros
      </a>
      <a class="item ms-4 {{ request()->routeIs('admin.puntos.estadisticas') ? 'active' : '' }}"
         href="{{ route('admin.puntos.estadisticas') }}">
        <i class="fa-regular fa-circle"></i> Estadísticas
      </a>
    </div>

    {{-- Canjeos (submenú) --}}
    @php $canjeosOpen = request()->routeIs('admin.canjeos.*'); @endphp
    <a class="item d-flex justify-content-between align-items-center {{ $canjeosOpen ? 'active' : '' }}"
       data-bs-toggle="collapse" href="#menuCanjeos" role="button" aria-expanded="{{ $canjeosOpen ? 'true':'false' }}">
      <span><i class="fa-solid fa-gift"></i> Canjeos</span>
      <i class="fa-solid fa-chevron-down"></i>
    </a>
    <div class="collapse {{ $canjeosOpen ? 'show' : '' }}" id="menuCanjeos">
      <a class="item ms-4 {{ request()->routeIs('admin.canjeos.index') ? 'active' : '' }}"
         href="{{ route('admin.canjeos.index') }}">
        <i class="fa-regular fa-circle"></i> Gestión de canjeos
      </a>
      <a class="item ms-4 {{ request()->routeIs('admin.canjeos.estadisticas') ? 'active' : '' }}"
         href="{{ route('admin.canjeos.estadisticas') }}">
        <i class="fa-regular fa-circle"></i> Estadísticas
      </a>
    </div>

    {{-- Botellas / Recompensas (futuros módulos) --}}
    <a class="item" href="#"><i class="fa-solid fa-bottle-water"></i> Botellas PET</a>
    <a class="item {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}"
       href="{{ route('admin.productos.index') }}">
      <i class="fa-solid fa-trophy"></i> Recompensas
    </a>

    {{-- Estadísticas (submenú) --}}
    @php $statsOpen = request()->routeIs('admin.dashboard') && str_starts_with(request('panel','overview'),'stats.'); @endphp
    <a class="item d-flex justify-content-between align-items-center {{ $statsOpen ? 'active' : '' }}"
       data-bs-toggle="collapse" href="#menuStats" role="button" aria-expanded="{{ $statsOpen ? 'true':'false' }}">
      <span><i class="fa-solid fa-chart-line"></i> Estadísticas</span>
      <i class="fa-solid fa-chevron-down"></i>
    </a>
    <div class="collapse {{ $statsOpen ? 'show' : '' }}" id="menuStats">
      <a class="item ms-4 {{ request('panel')==='stats.top' ? 'active' : '' }}"
         href="{{ route('admin.estadisticas.index',['panel'=>'stats.top','range'=>request('range','week')]) }}">
        <i class="fa-regular fa-circle"></i> Top 5 recolectores
      </a>
      <a class="item ms-4 {{ request('panel')==='stats.products' ? 'active' : '' }}"
         href="{{ route('admin.estadisticas.index',['panel'=>'stats.products','range'=>request('range','week')]) }}">
        <i class="fa-regular fa-circle"></i> Productos más canjeados
      </a>
    </div>

    <div class="section-title">Sistema</div>
    <a class="item" href="#"><i class="fa-solid fa-gear"></i> Configuración</a>
  </nav>

  {{-- Perfil + Logout (igual que ya tenías) --}}
  <div class="mt-auto pt-3">
    <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:rgba(255,255,255,.12)">
      <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;">
        <i class="fa-solid fa-user text-success"></i>
      </div>
      <div>
        <div style="font-weight:600">Admin EcoRecicla</div>
        <div style="opacity:.8; font-size:.85rem">Últ. acceso: Hoy</div>
      </div>
    </div>
    <form class="mt-2" method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="btn btn-light btn-sm w-100"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Salir</button>
    </form>
  </div>
</aside>

  {{-- MAIN --}}
  <main class="main">
    {{-- TOPBAR --}}
    <div class="topbar">
      <h1 class="title">@yield('header','Dashboard')</h1>
      <div class="search">
        <i class="fa-solid fa-magnifying-glass text-muted"></i>
        <input type="search" placeholder="Buscar…">
      </div>
      <div class="avatar-btn">
        <i class="fa-regular fa-circle-user"></i>
        <span>{{ auth()->user()->nombre ?? 'Admin' }}</span>
        <i class="fa-solid fa-chevron-down text-muted"></i>
      </div>
    </div>

    {{-- FLASHES --}}
    @if(session('success'))
      <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @if(session('ok'))
      <div class="alert alert-success py-2">{{ session('ok') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif

    {{-- CONTENT --}}
    @yield('content')

    @stack('scripts')
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
