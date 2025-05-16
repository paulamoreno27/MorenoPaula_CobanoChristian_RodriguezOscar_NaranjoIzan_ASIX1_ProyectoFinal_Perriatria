<?php
session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perriatria Veterinario - Inicio</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tuffy:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
  <link rel="icon" href="./resources/logo_perriatria.png" type="image/x-icon">
  <link rel="stylesheet" href="./css/styles.css">
</head>
<header class="text-center">
    <h1>Perriatria</h1>
</header>
<body>
  <ul class="nav nav-tabs custom-navbar w-100">
    <div class="nav-left">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="./index.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./view/mascotas.php">Mascotas</a>
      </li>
    </div>
    <div class="nav-right">
      <li class="nav-item">
        <a class="nav-link" href="./view/login.php">Iniciar sesión</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./view/register.php">Darse de alta</a>
      </li>
    </div>
  </ul>


  <main class="container mt-4">
    <section>
      <h2 class="seccion-titulo">¿Quiénes Somos?</h2>
      <p>En Perriatria Veterinario, somos una clínica veterinaria dedicada con pasión y compromiso al cuidado de tus mascotas.</p>
      <p>Nuestro equipo está formado por profesionales con amplia experiencia en medicina veterinaria preventiva, diagnóstico clínico y atención quirúrgica, especializados en perros y gatos, aunque también atendemos otras especies domésticas.</p>
      <p>Nuestra misión es brindar un servicio cercano, honesto y de calidad, centrado en el bienestar animal y el acompañamiento a sus familias en cada etapa de la vida de su compañero peludo.</p>
      <p>Creemos que la salud de tu mascota es parte de tu bienestar, por eso tratamos cada caso con sensibilidad, profesionalismo y calidez.</p>
      <img src="./resources/centro_veterinario.jpg" alt="Centro Veterinario" class="">
    </section>
    <section>
      <h2 class="seccion-titulo">Servicios</h2>
      <ul class="lista-servicios">
        <li>Consultas médicas generales</li>
        <li>Vacunación y desparasitación</li>
        <li>Cirugías programadas y de urgencia</li>
        <li>Análisis clínicos y diagnósticos por imagen</li>
        <li>Atención a cachorros y geriatría veterinaria</li>
        <li>Planes de salud preventiva</li>
        <li>Identificación por microchip</li>
        <li>Certificados de viaje y documentación oficial</li>
        <li>Asesoramiento nutricional personalizado</li>
    </ul>
    </section>
    <section>
      <h2 class="seccion-titulo">Contáctanos</h2>
    <div class="contacto-info">
        <p><strong>Dirección:</strong> Calle Sabino Arana 117, Barcelona</p>
        <p><strong>Teléfono:</strong> +34 93 12 39 416</p>
        <p><strong>Email:</strong> info.contacto@perriatria.com // pedir.cita@cperriatria.com</p>
        <p><strong>WhatsApp:</strong> +34 600 123 723</p>
        <p><strong>Horarios:</strong><br>
            Lunes a Viernes: 9:00 – 19:00<br>
            Sábados: 9:00 – 13:00<br>
            Urgencias: 24 horas, 7 días a la semana<br>
        </p>
    </div>
    </section>
  </main>
  <div class="logo-background"></div>
  <?php if (isset($_SESSION['usuario'])): ?>
      <div class="user-panel">
          <span>Bienvenido, <?php echo $_SESSION['usuario']; ?></span>
          <form action="./processes/logout.proc.php" method="post">
              <button type="submit">Cerrar sesión</button>
          </form>
      </div>
  <?php endif; ?>
  <div class="scroll-top-panel">
      <button onclick="window.scrollTo({top: 0, behavior: 'smooth'});">Volver arriba</button>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<footer class="footer">
  <p>&copy; 2023 Perriatria Veterinario. Todos los derechos reservados.</p>
</footer>
</html>
