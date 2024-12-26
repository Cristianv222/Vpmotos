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
        <input type="range" class="volume-control" min="0" max="1" step="0.1" value="0">
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
          <img src="imagenes/servicio1.jpg" alt="Servicio 1">
        </div>
        <div class="back">
          <h3>Servicio 1</h3>
          <p>Descripción del servicio 1 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="imagenes/servicio2.jpg" alt="Servicio 2">
        </div>
        <div class="back">
          <h3>Servicio 2</h3>
          <p>Descripción del servicio 2 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="imagenes/servicio3.jpg" alt="Servicio 3">
        </div>
        <div class="back">
          <h3>Servicio 3</h3>
          <p>Descripción del servicio 3 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="imagenes/servicio3.jpg" alt="Servicio 4">
        </div>
        <div class="back">
          <h3>Servicio 4 </h3>
          <p>Descripción del servicio 4 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="imagenes/servicio3.jpg" alt="Servicio 5">
        </div>
        <div class="back">
          <h3>Servicio 5 </h3>
          <p>Descripción del servicio 4 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>
    </div>
  </section>
  <?php include('./includes/footer.php'); ?>
  <script src="./js/scripts.js"></script>
</body>
</html>
