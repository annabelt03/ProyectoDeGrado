<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>@yield('title','EcoRecicla PET — Estudiante')</title>

  {{-- Bootstrap + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    :root{
      --primary-green: #4CAF50;
      --dark-green: #2F4F4F;
      --brown: #8B5E3C;
      --accent: #A0522D;
      --beige: #F5F5DC;
      --text-dark: #2E2E2E;
      --text-muted: #5C5C5C;
      --primary: var(--primary-green);
      --primary-dark: var(--dark-green);
      --primary-light: #d1fae5;
      --secondary: #f59e0b;
      --accent-color: #3b82f6;
      --background: var(--beige);
      --card-bg: #ffffff;
      --text: var(--text-dark);
      --border: #e5e7eb;
    }

    html,body{height:100%; margin:0; padding:0}
    body{background:var(--background); color:var(--text); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}

    /* LAYOUT RESPONSIVE */
    .student-layout{
      display:grid;
      grid-template-columns: 280px 1fr;
      min-height:100vh;
    }

    @media (max-width: 992px) {
      .student-layout{
        grid-template-columns: 1fr;
      }

      .student-sidebar{
        position: fixed;
        left: -280px;
        top: 0;
        z-index: 1050;
        transition: left 0.3s ease;
      }

      .student-sidebar.mobile-open{
        left: 0;
      }

      .student-main{
        margin-left: 0 !important;
      }

      .mobile-menu-btn{
        display: block !important;
      }
    }

    /* SIDEBAR ESTUDIANTE */
    .student-sidebar{
      background: linear-gradient(180deg, var(--dark-green) 0%, var(--primary-green) 100%);
      color:white;
      padding:20px 16px;
      position:sticky;
      top:0;
      height:100vh;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      overflow-y: auto;
    }

    .student-brand{
      display:flex;
      gap:12px;
      align-items:center;
      font-weight:700;
      color:white;
      text-decoration:none;
      font-size:1.3rem;
      margin-bottom:10px;
      padding:10px;
      border-radius:12px;
      background:rgba(255,255,255,0.1);
      transition: all 0.3s ease;
    }

    .student-brand:hover{
      background:rgba(255,255,255,0.2);
      transform: translateY(-2px);
    }

    .student-brand i{
      font-size:1.5rem;
      color:#d1fae5;
    }

    /* USER INFO TOP */
    .user-info-top {
      background: rgba(255,255,255,0.15);
      border-radius: 12px;
      padding: 12px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .user-details {
      display: flex;
      align-items: center;
      gap: 10px;
      flex: 1;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary-green);
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    .user-text{
      flex: 1;
      min-width: 0;
    }

    .user-text h4 {
      margin: 0;
      font-size: 0.95rem;
      font-weight: 600;
      color: white;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .user-text p {
      margin: 0;
      font-size: 0.8rem;
      opacity: 0.9;
      color: #e0f2fe;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .logout-btn {
      background: rgba(255,255,255,0.2);
      border: none;
      color: white;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      flex-shrink: 0;
    }

    .logout-btn:hover {
      background: rgba(255,255,255,0.3);
      transform: scale(1.1);
    }

    .student-menu{
      flex: 1;
    }

    .student-menu .item{
      display:flex;
      align-items:center;
      gap:12px;
      padding:12px 16px;
      border-radius:12px;
      color:#e0f2fe;
      text-decoration:none;
      margin:6px 0;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    .student-menu .item i{
      width:20px;
      text-align:center;
      font-size:1.1rem;
      flex-shrink: 0;
    }

    .student-menu .item:hover{
      background:rgba(255,255,255,0.15);
      transform: translateX(5px);
    }

    .student-menu .item.active{
      background:white;
      color:var(--dark-green);
      box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .student-menu .item.active i{
      color:var(--dark-green);
    }

    .points-display{
      background:rgba(255,255,255,0.15);
      border-radius:16px;
      padding:16px;
      margin:20px 0;
      text-align:center;
      border:2px dashed rgba(255,255,255,0.3);
    }

    .points-value{
      font-size:2rem;
      font-weight:800;
      color:white;
      margin:8px 0;
    }

    .points-label{
      font-size:0.9rem;
      opacity:0.9;
    }

    /* Logout form en el footer del sidebar */
    .sidebar-footer {
      margin-top: auto;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.2);
    }

    .logout-form {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255,255,255,0.1);
      padding: 12px;
      border-radius: 12px;
      color: white;
    }

    .logout-form i {
      font-size: 1.1rem;
      opacity: 0.9;
      flex-shrink: 0;
    }

    .logout-form span {
      flex: 1;
      font-size: 0.9rem;
      white-space: nowrap;
    }

    .logout-form button {
      background: rgba(255,255,255,0.2);
      border: none;
      color: white;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 0.8rem;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    .logout-form button:hover {
      background: rgba(255,255,255,0.3);
    }

    /* MAIN CONTENT */
    .student-main{
      padding:20px 24px;
      background:var(--background);
      min-height: 100vh;
    }

    @media (max-width: 768px) {
      .student-main{
        padding: 15px;
      }
    }

    .student-topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      margin-bottom:24px;
      padding:16px 0;
      flex-wrap: wrap;
      gap: 15px;
    }

    @media (max-width: 576px) {
      .student-topbar{
        flex-direction: column;
        align-items: flex-start;
      }

      .action-buttons{
        width: 100%;
        justify-content: space-between;
      }
    }

    .page-title{
      color:var(--dark-green);
      font-weight:700;
      font-size:1.8rem;
      margin:0;
    }

    @media (max-width: 768px) {
      .page-title{
        font-size: 1.5rem;
      }
    }

    .welcome-message{
      color:var(--text-muted);
      font-size:1rem;
      margin-top:4px;
    }

    .action-buttons{
      display:flex;
      gap:12px;
      align-items:center;
      flex-wrap: wrap;
    }

    /* Botón menú móvil */
    .mobile-menu-btn{
      display: none;
      background: var(--primary-green);
      border: none;
      color: white;
      width: 44px;
      height: 44px;
      border-radius: 12px;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      transition: all 0.3s ease;
    }

    .mobile-menu-btn:hover{
      background: var(--dark-green);
      transform: scale(1.05);
    }

    /* Botones de acción */
    .btn-primary-custom {
      background: var(--primary-green);
      border: none;
      color: white;
      padding: 12px 24px;
      border-radius: 20px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-primary-custom:hover {
      background: var(--dark-green);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .btn-secondary-custom {
      background: var(--accent-color);
      border: none;
      color: white;
      padding: 12px 24px;
      border-radius: 20px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-secondary-custom:hover {
      background: #2563eb;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* CARDS */
    .student-card{
      background:var(--card-bg);
      border-radius:16px;
      padding:24px;
      margin-bottom:24px;
      box-shadow:0 4px 12px rgba(0,0,0,0.05);
      border:1px solid var(--border);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .student-card:hover{
      transform: translateY(-2px);
      box-shadow:0 8px 24px rgba(0,0,0,0.1);
    }

    .card-title{
      color:var(--dark-green);
      font-weight:600;
      margin-bottom:16px;
      font-size:1.2rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .card-title i{
      color: var(--primary-green);
    }

    /* BADGES */
    .badge-eco{
      background:var(--primary-light);
      color:var(--dark-green);
      padding:6px 12px;
      border-radius:20px;
      font-weight:600;
      font-size:0.8rem;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    /* Alertas */
    .alert {
      border-radius: 12px;
      border: none;
      margin-bottom: 20px;
      padding: 16px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .alert-success {
      background: #d1fae5;
      color: var(--dark-green);
      border-left: 4px solid var(--primary-green);
    }

    .alert-danger {
      background: #fee2e2;
      color: #dc2626;
      border-left: 4px solid #dc2626;
    }

    .alert-warning {
      background: #fef3c7;
      color: #d97706;
      border-left: 4px solid #f59e0b;
    }

    /* Overlay para móvil */
    .mobile-overlay{
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 1040;
    }

    .mobile-overlay.active{
      display: block;
    }

    /* Stats Cards */
    .stats-grid{
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card{
      background: white;
      border-radius: 16px;
      padding: 24px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      border: 1px solid var(--border);
      transition: all 0.3s ease;
    }

    .stat-card:hover{
      transform: translateY(-3px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }

    .stat-icon{
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      font-size: 1.5rem;
    }

    .stat-icon.primary{
      background: var(--primary-light);
      color: var(--primary-green);
    }

    .stat-icon.success{
      background: #d1fae5;
      color: var(--primary-green);
    }

    .stat-icon.warning{
      background: #fef3c7;
      color: #f59e0b;
    }

    .stat-icon.info{
      background: #dbeafe;
      color: var(--accent-color);
    }

    .stat-value{
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-green);
      margin-bottom: 5px;
    }

    .stat-label{
      color: var(--text-muted);
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
<div class="student-layout">
  {{-- SIDEBAR ESTUDIANTE --}}
  <aside class="student-sidebar" id="sidebar">
    {{-- Logo --}}
    <a href="{{ route('estudiante.dashboard') }}" class="student-brand">
      <i class="fa-solid fa-recycle"></i>
      EcoRecicla PET
    </a>

    {{-- Información del usuario arriba --}}
    <div class="user-info-top">
      <div class="user-details">
        <div class="user-avatar">
          <i class="fa-solid fa-user-graduate"></i>
        </div>
        <div class="user-text">
          <h4>{{ auth()->user()->nombre ?? 'Estudiante' }}</h4>
          <p>Estudiante</p>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn" title="Cerrar sesión">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </button>
      </form>
    </div>

    {{-- Display de Puntos --}}
    <div class="points-display">
      <div class="points-label">Tus Puntos Disponibles</div>
      <div class="points-value">{{ number_format($puntosTotales ?? $puntosDisponibles ?? 0, 2) }}</div>
      <div class="points-label">¡Sigue reciclando!</div>
    </div>

    <nav class="student-menu">
      {{-- Dashboard --}}
      <a class="item {{ request()->routeIs('estudiante.dashboard') ? 'active' : '' }}"
         href="{{ route('estudiante.dashboard') }}">
        <i class="fa-solid fa-house"></i> Dashboard
      </a>

      {{-- Mis Puntos --}}
      <a class="item {{ request()->routeIs('estudiante.mis-puntos') ? 'active' : '' }}"
         href="{{ route('estudiante.mis-puntos') }}">
        <i class="fa-solid fa-coins"></i> Mis Puntos
      </a>

      {{-- Canjear Productos --}}
      <a class="item {{ request()->routeIs('estudiante.canjear-productos') ? 'active' : '' }}"
         href="{{ route('estudiante.canjear-productos') }}">
        <i class="fa-solid fa-gift"></i> Canjear Productos
      </a>

      {{-- Mi Historial --}}
      <a class="item {{ request()->routeIs('estudiante.historial-puntos') ? 'active' : '' }}"
         href="{{ route('estudiante.historial-puntos') }}">
        <i class="fa-solid fa-history"></i> Historial de Puntos
      </a>

      {{-- Historial de Canjes --}}
      <a class="item {{ request()->routeIs('estudiante.historial-canjes') ? 'active' : '' }}"
         href="{{ route('estudiante.historial-canjes') }}">
        <i class="fa-solid fa-exchange-alt"></i> Historial de Canjes
      </a>

      {{-- Perfil --}}
      <a class="item" href="#">
        <i class="fa-solid fa-user"></i> Mi Perfil
      </a>
    </nav>

    {{-- Cerrar sesión en el footer --}}
    <div class="sidebar-footer">
      <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <span>Cerrar sesión</span>
        <button type="submit" class="btn-light btn-sm">Salir</button>
      </form>
    </div>
  </aside>

  {{-- OVERLAY PARA MÓVIL --}}
  <div class="mobile-overlay" id="mobileOverlay"></div>

  {{-- MAIN CONTENT --}}
  <main class="student-main">
    {{-- TOPBAR --}}
    <div class="student-topbar">
      <div style="display: flex; align-items: center; gap: 15px;">
        <button class="mobile-menu-btn" id="mobileMenuBtn">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div>
          <h1 class="page-title">@yield('header', 'Mi Dashboard')</h1>
          <div class="welcome-message">
            ¡Bienvenido de nuevo, {{ auth()->user()->nombre ?? 'Estudiante' }}!
            <span class="badge-eco">
              <i class="fa-solid fa-coins"></i>
              {{ number_format($puntosDisponibles ?? auth()->user()->puntos ?? 0, 2) }} puntos disponibles
            </span>
          </div>
        </div>
      </div>

      <div class="action-buttons">
        <a href="{{ route('estudiante.mis-puntos') }}" class="btn-primary-custom">
          <i class="fa-solid fa-chart-line"></i> Ver Mis Puntos
        </a>
        <a href="{{ route('estudiante.canjear-productos') }}" class="btn-secondary-custom">
          <i class="fa-solid fa-gift"></i> Canjear Puntos
        </a>
      </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle"></i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-exclamation-triangle"></i>
        <div class="flex-grow-1">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-exclamation-circle"></i>
        <div class="flex-grow-1">{{ $errors->first() }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- CONTENT --}}
    @yield('content')

  </main>
</div>

<script>
// Menú móvil
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileOverlay = document.getElementById('mobileOverlay');

  function toggleSidebar() {
    sidebar.classList.toggle('mobile-open');
    mobileOverlay.classList.toggle('active');
    document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
  }

  mobileMenuBtn.addEventListener('click', toggleSidebar);
  mobileOverlay.addEventListener('click', toggleSidebar);

  // Cerrar sidebar al hacer clic en un enlace (en móvil)
  if (window.innerWidth <= 992) {
    const menuLinks = document.querySelectorAll('.student-menu .item');
    menuLinks.forEach(link => {
      link.addEventListener('click', toggleSidebar);
    });
  }

  // Ajustar altura del contenido
  function adjustContentHeight() {
    const sidebar = document.querySelector('.student-sidebar');
    const main = document.querySelector('.student-main');

    if (window.innerWidth > 992) {
      main.style.minHeight = sidebar.scrollHeight + 'px';
    } else {
      main.style.minHeight = '100vh';
    }
  }

  window.addEventListener('resize', adjustContentHeight);
  adjustContentHeight();
});
</script>

@stack('scripts')
</body>
</html>
