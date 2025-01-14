<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/carruselinf.css">
  <link rel="stylesheet" href="./css/index.css">
  <title>Vpmotos</title>
</head>
<body>
<?php include './includes/menu.php'; ?>
<div class="carrusel-container">
    <div class="carrusel">
      <!-- Slide 1 - Imagen -->
      <div class="slide">
        <video controls>
          <source src="./images/Motorcycle_Cinematic_Video.mp4" type="video/mp4">
          Tu navegador no soporta el formato de video.
        </video>
      </div>
      
      <!-- Slide 2 - Imagen -->
      <div class="slide">
        <img src="./images/poratada_1.png" alt="Imagen 2">
      </div>


      <!-- Slide 4 - Imagen -->
      <div class="slide">
        <img src="./images/alamcen_VP.jpg" alt="Imagen 3">
      </div>
    </div>

    <!-- Controles -->
    <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
    <button class="next" onclick="moveSlide(1)">&#10095;</button>
  </div>

  <main class="infinite-wrapper">
  <div class="infinite-carousel">
    <div class="infinite-item" style="background: url('./images/yam.jpg') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images/bajaj1.JPG') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images/Honda.png') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images/ktm1.JPG') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images/cfmoto.webp') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images/Suzuki.webp') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images//Shineray.png') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images/tuko.png') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images/benelli2.jpeg') center/cover;"></div>
    <div class="infinite-item" style="background: url('./images/Kawasaki.jpeg') center/cover;"></div>
  </div>
</main>

  <section class="servicios">
    <h2>Servicios que ofrecemos</h2>
    <div class="servicios-container">
      <div class="servicio-card">
        <div class="front">
          <img src="./images/ABC.png" alt="Servicio 1">
        </div>
        <div class="back">
        <ol>
    <li>Ajuste de presión de inflado de neumáticos</li>
    <li>Limpieza o cambio de bujías</li>
    <li>Limpieza, lubricación y ajuste de cadena</li>
    <li>Reajuste de tornillería principal</li>
    <li>Revisión y calibración de luces</li>
    <li>Lubricación de maniguetas</li>
    <li>Lubricación de cables (freno- embrague)</li>
    <li>Limpieza del orificio del drenaje del silenciador</li>
  </ol>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="./images/GASES.png" alt="Servicio 2">
        </div>
        <div class="back">
          <ol>
          </ol>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="./images/INYECTORES.png" alt="Servicio 3">
        </div>
        <div class="back">
          <h3>Servicio 3</h3>
          <p>Descripción del servicio 3 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="./images/scaner.png" alt="Servicio 4">
        </div>
        <div class="back">
          <h3>Servicio 4 </h3>
          <p>Descripción del servicio 4 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>
    </div>
  </section>
  <?php include('./includes/footer.php'); ?>
  <script src="./js/scripts.js"></script>
</body>
</html>
