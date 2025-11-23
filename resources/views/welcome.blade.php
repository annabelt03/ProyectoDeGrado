<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>EcoRecicla PET - Inicio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{
  --primary-green: #4CAF50;   /* Verde reciclaje */
  --dark-green: #2F4F4F;      /* Verde bosque */
  --brown: #8B5E3C;           /* Marrón madera */
  --accent: #A0522D;          /* Marrón rojizo */
  --beige: #F5F5DC;           /* Beige arena */
  --text-dark: #2E2E2E;
  --text-muted: #5C5C5C;
}

html,body{
  height:100%;
  margin:0;
  font-family:Poppins,system-ui,sans-serif;
  color:#fff;
  background:linear-gradient(135deg,var(--dark-green),var(--primary-green));
  background-attachment:fixed;
}

/* Estilos de botones adaptados del código original */
.btn-light {
  background-color: #fff !important;
  border-color: #fff !important;
  color: var(--text-dark) !important;
  font-weight: 500;
  border-radius: 20px;
  transition: .3s;
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
}

.btn-outline-light:hover {
  background-color: #fff !important;
  color: var(--text-dark) !important;
}

/* Navbar */
.navbar{
  background:linear-gradient(90deg,var(--dark-green) 0%,var(--primary-green) 100%);
  backdrop-filter:blur(6px);
}
.navbar-brand{
  color:#fff!important;
  font-weight:700;
  font-size:1.6rem;
  letter-spacing:1px;
}

.carousel{
  max-width:950px;
  margin:60px auto;
  border-radius:25px;
  overflow:hidden;
  box-shadow:0 8px 20px rgba(0,0,0,.3);
}
.carousel-item img{
  border-radius:25px;
  height:480px;
  object-fit:cover;
  opacity:.88;
  filter:brightness(.9);
}
.carousel-caption{
  background:rgba(0,0,0,.55);
  border-radius:15px;
  padding:20px;
  color:#fff;
}
footer{
  text-align:center;
  color:#fff;
  padding:20px;
  opacity:.9;
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="#"><i class="fas fa-recycle me-2"></i> EcoRecicla PET</a>
    <div class="ms-auto">
      <a href="{{ route('welcome') }}" class="btn btn-outline-light me-2">Inicio</a>
      <a href="#" class="btn btn-outline-light me-2">Quiénes somos</a>
      <a href="#" class="btn btn-outline-light me-2">Productos</a>
      <a href="#" class="btn btn-light me-2">Contactos</a>
      <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Iniciar Sesión</a>
      <!-- <a href="" class="btn btn-light">Iniciar Sesión Admin</a>-->
    </div>
  </div>
</nav>

<div style="margin-top:90px"></div>

<div id="carouselInfo" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <!-- Aquí iría el contenido de tu carrusel -->
    </div>
  </div>
</div>

<footer>
  © 2023 EcoRecicla PET — Educación, tecnología y reciclaje para Bolivia
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>