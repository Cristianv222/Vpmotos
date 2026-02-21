<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vpmotos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./includes/menu.css">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Audiowide', sans-serif;
      background: #0a0a0a;
      color: #fff;
      overflow-x: hidden;
    }

    /* ══════════════════════════
       HERO
    ══════════════════════════ */
    .hero-section {
      position: relative;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }

    .hero-video {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(
        180deg,
        rgba(0,0,0,0.20) 0%,
        rgba(0,0,0,0.00) 40%,
        rgba(0,0,0,0.00) 58%,
        rgba(0,0,0,0.55) 100%
      );
      z-index: 1;
      pointer-events: none;
    }

    /* Logo centrado con animación de entrada */
    .hero-logo-wrap {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 2;
      animation: logoEntrada 1.8s cubic-bezier(0.16, 1, 0.3, 1) both;
      will-change: opacity, transform, filter;
      pointer-events: none;
    }

    @keyframes logoEntrada {
      from { opacity: 0; transform: translateY(28px) scale(0.93); filter: blur(10px); }
      to   { opacity: 1; transform: translateY(0)    scale(1);    filter: blur(0);   }
    }

    .hero-logo-wrap img {
      width: clamp(150px, 22vw, 300px);
      filter:
        drop-shadow(0 0 40px rgba(0,0,0,1))
        drop-shadow(0 2px 12px rgba(0,0,0,0.9));
    }

    /* ══════════════════════════
       CARRUSEL DE LOGOS
       Franja inferior del hero
       — CSS puro, sin saltos —

       Lógica anti-salto:
       · Cada item = 100px exacto (sin padding, sin gap)
       · Track = 20 items → width: 2000px (fijo, declarado)
       · Keyframe mueve exactamente 1000px (10 items)
       · Al llegar a -1000px la animación vuelve a 0
         y el ojo ve exactamente lo mismo → loop invisible
    ══════════════════════════ */
    .carousel-strip {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 60px;
      z-index: 3;
      overflow: hidden;
      background: none;

      /* Fade en los bordes para efecto "infinito" */
      -webkit-mask-image: linear-gradient(
        to right,
        transparent  0%,
        black        6%,
        black       94%,
        transparent 100%
      );
      mask-image: linear-gradient(
        to right,
        transparent  0%,
        black        6%,
        black       94%,
        transparent 100%
      );
    }

    .carousel-track {
      display: flex;
      align-items: center;
      height: 100%;
      width: 2000px;            /* 20 × 100px — fijo y exacto */
      animation: marquee 20s linear infinite;
      will-change: transform;
    }

    .carousel-strip:hover .carousel-track {
      animation-play-state: paused;
    }

    /* Mueve exactamente el ancho de 1 set = 1000px */
    @keyframes marquee {
      from { transform: translateX(0);       }
      to   { transform: translateX(-1000px); }
    }

    .carousel-item {
      width: 100px;             /* fijo — no flex-grow, no padding */
      height: 60px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .carousel-item img {
      max-height: 36px;
      max-width: 82px;
      width: auto;
      height: auto;
      object-fit: contain;
      display: block;
      /* screen elimina fondos oscuros sobre el hero oscuro */
      mix-blend-mode: screen;
      filter: grayscale(100%) brightness(0.8);
      opacity: 0.6;
      transition: filter 0.4s, opacity 0.4s, transform 0.35s;
    }

    .carousel-item:hover img {
      filter: grayscale(0%) brightness(1.1);
      opacity: 1;
      transform: scale(1.12);
      cursor: pointer;
    }

    /* ══════════════════════════
       SERVICIOS
    ══════════════════════════ */
    .servicios {
      padding: 90px 50px;
      text-align: center;
      background-image:
        linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
        url('./images/imagen_servicios.jpg');
      background-position: center;
      background-size: cover;
      background-attachment: fixed;
      background-repeat: no-repeat;
      color: #fff;
    }

    .servicios h2 {
      font-family: 'Orbitron', sans-serif;
      font-size: clamp(1.8rem, 4vw, 4.5rem);
      font-weight: 400;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: #929292;
      margin-bottom: 50px;
    }

    .servicios-container {
      display: flex;
      justify-content: center;
      gap: 24px;
      flex-wrap: wrap;
    }

    .servicio-card {
      position: relative;
      width: 300px;
      height: 400px;
      perspective: 1000px;
    }

    .front, .back {
      position: absolute;
      inset: 0;
      backface-visibility: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 10px;
      transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .front { overflow: hidden; background: #eaeaea; }

    .front img {
      width: 100%; height: 100%;
      object-fit: cover;
      border-radius: 10px;
    }

    .back {
      font-family: 'Audiowide', sans-serif;
      background-color: #181717;
      border: 1px solid rgba(210,237,5,0.1);
      color: rgb(192, 196, 187);
      padding: 24px;
      text-align: left;
      transform: rotateY(180deg);
      flex-direction: column;
      justify-content: flex-start;
      font-size: 13px;
      line-height: 1.7;
    }

    .back ol { margin: 0; padding-left: 20px; list-style-type: decimal; }
    .back li  { margin-bottom: 8px; }

    .servicio-card:hover .front { transform: rotateY(180deg); }
    .servicio-card:hover .back  { transform: rotateY(0); }

    @media (max-width: 768px) {
      .servicios { padding: 60px 20px; }
      .servicio-card { width: 260px; height: 360px; }
    }
  </style>
</head>
<body>

  <?php include './includes/menu.php'; ?>

  <!-- ══════════ HERO ══════════ -->
  <div class="hero-section">

    <!-- Video de fondo: autoplay, muted, loop -->
    <video class="hero-video" autoplay muted loop playsinline preload="auto">
      <source src="./images/Motorcycle_Cinematic_Video.mp4" type="video/mp4">
      Tu navegador no soporta el formato de video.
    </video>

    <div class="hero-overlay"></div>

    <!-- Logo centrado con efecto de entrada -->
    <div class="hero-logo-wrap" id="heroLogo">
      <img src="./images/logo_vp.png" alt="Vpmotos">
    </div>

    <!-- Carrusel de logos — franja inferior flotante -->
    <div class="carousel-strip">
      <div class="carousel-track">

        <!-- Set 1 — original -->
        <div class="carousel-item"><img src="./images/yam.jpg"       alt="Yamaha"></div>
        <div class="carousel-item"><img src="./images/bajaj1.JPG"    alt="Bajaj"></div>
        <div class="carousel-item"><img src="./images/Honda.png"     alt="Honda"></div>
        <div class="carousel-item"><img src="./images/ktm1.JPG"      alt="KTM"></div>
        <div class="carousel-item"><img src="./images/cfmoto.webp"   alt="CFMoto"></div>
        <div class="carousel-item"><img src="./images/Suzuki.webp"   alt="Suzuki"></div>
        <div class="carousel-item"><img src="./images/Shineray.png"  alt="Shineray"></div>
        <div class="carousel-item"><img src="./images/tuko.png"      alt="Tuko"></div>
        <div class="carousel-item"><img src="./images/benelli2.jpeg" alt="Benelli"></div>
        <div class="carousel-item"><img src="./images/Kawasaki.jpeg" alt="Kawasaki"></div>

        <!-- Set 2 — clon idéntico para loop sin salto -->
        <div class="carousel-item"><img src="./images/yam.jpg"       alt="Yamaha"></div>
        <div class="carousel-item"><img src="./images/bajaj1.JPG"    alt="Bajaj"></div>
        <div class="carousel-item"><img src="./images/Honda.png"     alt="Honda"></div>
        <div class="carousel-item"><img src="./images/ktm1.JPG"      alt="KTM"></div>
        <div class="carousel-item"><img src="./images/cfmoto.webp"   alt="CFMoto"></div>
        <div class="carousel-item"><img src="./images/Suzuki.webp"   alt="Suzuki"></div>
        <div class="carousel-item"><img src="./images/Shineray.png"  alt="Shineray"></div>
        <div class="carousel-item"><img src="./images/tuko.png"      alt="Tuko"></div>
        <div class="carousel-item"><img src="./images/benelli2.jpeg" alt="Benelli"></div>
        <div class="carousel-item"><img src="./images/Kawasaki.jpeg" alt="Kawasaki"></div>

      </div>
    </div>

  </div>

  <!-- ══════════ SERVICIOS ══════════ -->
  <section class="servicios">
    <h2>Servicios que ofrecemos</h2>
    <div class="servicios-container">

      <div class="servicio-card">
        <div class="front"><img src="./images/ABC.png" alt="Servicio 1"></div>
        <div class="back">
          <ol>
            <li>Ajuste de presión de inflado de neumáticos</li>
            <li>Limpieza o cambio de bujías</li>
            <li>Limpieza, lubricación y ajuste de cadena</li>
            <li>Reajuste de tornillería principal</li>
            <li>Revisión y calibración de luces</li>
            <li>Lubricación de maniguetas</li>
            <li>Lubricación de cables (freno - embrague)</li>
            <li>Limpieza del orificio del drenaje del silenciador</li>
          </ol>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front"><img src="./images/GASES.png" alt="Servicio 2"></div>
        <div class="back"><ol></ol></div>
      </div>

      <div class="servicio-card">
        <div class="front"><img src="./images/INYECTORES.png" alt="Servicio 3"></div>
        <div class="back">
          <h3>Servicio 3</h3>
          <p>Descripción del servicio 3 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front"><img src="./images/scaner.png" alt="Servicio 4"></div>
        <div class="back">
          <h3>Servicio 4</h3>
          <p>Descripción del servicio 4 que ofrecemos. Detalles sobre el servicio.</p>
        </div>
      </div>

    </div>
  </section>

  <?php include('./includes/footer.php'); ?>

  <script src="./js/scripts.js"></script>

  <script>
    /* Logo del hero: se desvanece al hacer scroll */
    (function () {
      const logo = document.getElementById('heroLogo');
      const hero = document.querySelector('.hero-section');
      window.addEventListener('scroll', function () {
        var ratio = Math.min(window.scrollY / (hero.offsetHeight * 0.4), 1);
        var e = ratio * ratio;
        logo.style.opacity   = String(1 - e);
        logo.style.transform = 'translateY(' + (-e * 30) + 'px) scale(' + (1 - e * 0.04) + ')';
        logo.style.filter    = 'blur(' + (e * 5) + 'px)';
      });
    })();
  </script>

</body>
</html> 