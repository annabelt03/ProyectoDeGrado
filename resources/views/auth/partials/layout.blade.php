{{-- resources/views/auth/partials/layout.blade.php --}}
<!doctype html>
<html lang="es">
        <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1"/>
        <title>@yield('title','EcoRecicla PET')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <style>
        :root{--primary-green:#047656;--light-green:#4ddc9f;--accent-green:#16a34a}
        body{background:linear-gradient(135deg,var(--primary-green),var(--light-green));min-height:100vh;display:flex;align-items:center}
        </style>
        </head>
        <body>
        <div class="container">
        <div class="text-center mb-4">
        <a href="{{ route('welcome') }}" class="text-white text-decoration-none fw-bold fs-4">
        <i class="fas fa-recycle me-2"></i> EcoRecicla PET
        </a>
        </div>
        @yield('content')
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </body>
</html>