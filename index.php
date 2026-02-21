<!DOCTYPE html>
<html lang="es-EC">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ═══ SEO ═══════════════════════════════════════════════════════ -->
  <title>VP Motos Quito | Repuestos, Accesorios y Llantas para Moto en Ecuador</title>
  <meta name="description" content="Tienda de repuestos originales, accesorios, llantas y aceites para motos en Quito, Ecuador. Yamaha, Honda, Bajaj, KTM, Kawasaki. Servicio técnico especializado. ¡Compra online!">
  <meta name="keywords" content="repuestos motos Quito, accesorios motos Ecuador, llantas moto Quito, aceite moto Ecuador, repuestos Honda Ecuador, repuestos Yamaha Quito, tienda motos Quito, VP Motos">
  <meta name="author" content="VP Motos">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <link rel="canonical" href="https://www.vpmotos.ec/">
  <meta name="geo.region" content="EC-P">
  <meta name="geo.placename" content="Quito, Pichincha, Ecuador">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.vpmotos.ec/">
  <meta property="og:title" content="VP Motos Quito | Repuestos y Accesorios para Moto en Ecuador">
  <meta property="og:description" content="Repuestos originales, accesorios, llantas y aceites para motos en Quito. Yamaha, Honda, Bajaj, KTM, Kawasaki. Compra online con envío a todo Ecuador.">
  <meta property="og:image" content="https://www.vpmotos.ec/images/logo_vp.png">
  <meta property="og:locale" content="es_EC">
  <meta property="og:site_name" content="VP Motos">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="VP Motos Quito | Repuestos y Accesorios para Moto en Ecuador">
  <meta name="twitter:description" content="Repuestos originales, accesorios, llantas y aceites para motos en Quito Ecuador.">
  <meta name="twitter:image" content="https://www.vpmotos.ec/images/logo_vp.png">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="./images/logo_vp.png">
  <link rel="apple-touch-icon" href="./images/logo_vp.png">
  <meta name="theme-color" content="#D2ED05">

  <!-- Schema.org -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "AutoPartsStore",
    "name": "VP Motos",
    "description": "Tienda especializada en repuestos originales, accesorios, llantas y aceites para motocicletas en Quito, Ecuador.",
    "url": "https://www.vpmotos.ec",
    "logo": { "@type": "ImageObject", "url": "https://www.vpmotos.ec/images/logo_vp.png" },
    "telephone": "+593996628440",
    "address": { "@type": "PostalAddress", "addressLocality": "Quito", "addressRegion": "Pichincha", "addressCountry": "EC" },
    "areaServed": { "@type": "Country", "name": "Ecuador" },
    "sameAs": ["https://www.facebook.com/profile.php?id=61553909536855", "https://www.tiktok.com/@vpmotos"]
  }
  </script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Navbar CSS -->
  <link rel="stylesheet" href="./includes/menu.css">

  <style>
    /* ══════════════════════════════════════════════════════
       RESET & ROOT
    ══════════════════════════════════════════════════════ */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --accent:    #D2ED05;
      --accent2:   #a8bc04;
      --bg:        #0a0a0a;
      --surface:   #111111;
      --surface2:  #1a1a1a;
      --border:    rgba(255,255,255,0.07);
      --text:      #e8e8e8;
      --muted:     rgba(255,255,255,0.4);
    }

    body {
      font-family: 'Audiowide', sans-serif;
      background: var(--bg);
      color: var(--text);
      overflow-x: hidden;
    }

    /* ══════════════════════════════════════════════════════
       HERO
    ══════════════════════════════════════════════════════ */
    .hero-section {
      position: relative;
      width: 100%;
      height: 100vh;
      min-height: 600px;
      overflow: hidden;
    }

    .hero-video {
      position: absolute;
      inset: 0;
      width: 100%; height: 100%;
      object-fit: cover;
      z-index: 0;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(
        180deg,
        rgba(0,0,0,0.15) 0%,
        rgba(0,0,0,0.00) 35%,
        rgba(0,0,0,0.00) 52%,
        rgba(0,0,0,0.80) 100%
      );
      z-index: 1;
      pointer-events: none;
    }

    /* ── Logo + CTA centrado ─────────────────────────── */
    .hero-center {
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 2;
      gap: 36px;
      animation: heroEntrada 1.8s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes heroEntrada {
      from { opacity: 0; transform: translateY(24px); filter: blur(8px); }
      to   { opacity: 1; transform: translateY(0);    filter: blur(0);   }
    }

    /* LOGO — corregido: auto en ambas dimensiones para no aplastarlo */
    .hero-logo {
      width: auto;
      height: auto;
      max-width: clamp(200px, 28vw, 380px);
      max-height: 240px;
      object-fit: contain;          /* nunca se comprime */
      pointer-events: none;
      filter:
        drop-shadow(0 0 60px rgba(0,0,0,0.95))
        drop-shadow(0 4px 20px rgba(0,0,0,0.85));
    }

    /* ── Botón tienda ─────────────────────────────────── */
    .hero-cta-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 14px;
      animation: ctaUp 2.3s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes ctaUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .btn-hero {
      display: inline-flex;
      align-items: center;
      gap: 14px;
      padding: 18px 48px;
      background: var(--accent);
      color: #000;
      font-family: 'Orbitron', sans-serif;
      font-size: clamp(0.7rem, 1.4vw, 0.9rem);
      font-weight: 700;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      text-decoration: none;
      border-radius: 3px;
      position: relative;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 0 8px 40px rgba(210,237,5,0.4);
    }

    .btn-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: #000;
      transform: scaleX(0);
      transform-origin: right;
      transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-hero:hover::before { transform: scaleX(1); transform-origin: left; }
    .btn-hero:hover { color: var(--accent); box-shadow: 0 16px 60px rgba(210,237,5,0.5); transform: translateY(-3px); }

    .btn-hero i,
    .btn-hero span { position: relative; z-index: 1; }

    .btn-hero i { font-size: 1.1rem; }

    .hero-sub {
      font-size: 0.6rem;
      letter-spacing: 0.28em;
      color: rgba(255,255,255,0.45);
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .hero-sub-dot {
      width: 3px; height: 3px;
      background: var(--accent);
      border-radius: 50%;
      opacity: .6;
    }

    /* ── Scroll hint ──────────────────────────────────── */
    .hero-scroll {
      position: absolute;
      bottom: 72px; left: 50%;
      transform: translateX(-50%);
      z-index: 3;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      animation: scrollBounce 2.4s ease-in-out infinite;
      pointer-events: none;
      opacity: .45;
    }

    @keyframes scrollBounce {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50%       { transform: translateX(-50%) translateY(9px); }
    }

    .hero-scroll span {
      font-size: 0.48rem;
      letter-spacing: 0.3em;
      color: #fff;
    }

    .hero-scroll i { font-size: 1rem; color: #fff; }

    /* ── Carrusel marcas ──────────────────────────────── */
    .carousel-strip {
      position: absolute;
      bottom: 0; left: 0;
      width: 100%; height: 60px;
      z-index: 3;
      overflow: hidden;
      -webkit-mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
      mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
    }

    .carousel-track {
      display: flex;
      align-items: center;
      height: 100%;
      width: 2000px;
      animation: marquee 22s linear infinite;
    }

    .carousel-strip:hover .carousel-track { animation-play-state: paused; }

    @keyframes marquee {
      from { transform: translateX(0); }
      to   { transform: translateX(-1000px); }
    }

    .carousel-item {
      width: 100px; height: 60px;
      flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
    }

    .carousel-item img {
      max-height: 34px; max-width: 80px;
      width: auto; height: auto;
      object-fit: contain;
      mix-blend-mode: screen;
      filter: grayscale(100%) brightness(0.75);
      opacity: .55;
      transition: filter .4s, opacity .4s, transform .35s;
    }

    .carousel-item:hover img {
      filter: grayscale(0%) brightness(1.1);
      opacity: 1;
      transform: scale(1.14);
    }

    /* ══════════════════════════════════════════════════════
       BANDA SEO
    ══════════════════════════════════════════════════════ */
    .seo-band {
      background: #0d0d0d;
      border-top: 1px solid rgba(210,237,5,0.06);
      border-bottom: 1px solid rgba(210,237,5,0.06);
      padding: 18px 60px;
      text-align: center;
    }

    .seo-band p {
      font-size: 0.58rem;
      letter-spacing: 0.1em;
      color: rgba(255,255,255,0.15);
      line-height: 1.9;
      max-width: 880px;
      margin: 0 auto;
    }

    .seo-band strong { color: rgba(210,237,5,0.2); }

    /* ══════════════════════════════════════════════════════
       SECCIÓN TIENDA — CATEGORÍAS
    ══════════════════════════════════════════════════════ */
    .tienda-section {
      padding: 100px 60px;
      background: var(--bg);
      position: relative;
    }

    .tienda-section::before {
      content: '';
      position: absolute;
      top: 0; left: 50%; transform: translateX(-50%);
      width: 1px; height: 60px;
      background: linear-gradient(to bottom, transparent, rgba(210,237,5,0.4));
    }

    .section-header {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-tag {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 0.58rem;
      letter-spacing: 0.3em;
      color: var(--accent);
      text-transform: uppercase;
      margin-bottom: 14px;
    }

    .section-tag i { font-size: 0.75rem; }

    .section-header h2 {
      font-family: 'Orbitron', sans-serif;
      font-size: clamp(1.7rem, 3.5vw, 2.8rem);
      font-weight: 400;
      letter-spacing: 0.12em;
      color: #fff;
      margin-bottom: 12px;
    }

    .section-header h2 em { color: var(--accent); font-style: normal; }

    .section-header p {
      font-size: 0.68rem;
      color: var(--muted);
      letter-spacing: 0.1em;
      line-height: 1.9;
      max-width: 480px;
      margin: 0 auto;
    }

    /* Grid categorías */
    .cat-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 50px;
    }

    .cat-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 36px 24px;
      text-align: center;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 14px;
      position: relative;
      overflow: hidden;
      transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .cat-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(145deg, rgba(210,237,5,0.06) 0%, transparent 60%);
      opacity: 0;
      transition: opacity .3s;
    }

    .cat-card:hover {
      border-color: rgba(210,237,5,0.35);
      transform: translateY(-7px);
      box-shadow: 0 24px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(210,237,5,0.08);
    }

    .cat-card:hover::before { opacity: 1; }

    .cat-icon {
      width: 58px; height: 58px;
      background: rgba(210,237,5,0.07);
      border: 1px solid rgba(210,237,5,0.15);
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      transition: all .35s;
    }

    .cat-icon i {
      font-size: 1.5rem;
      color: var(--accent);
      transition: transform .35s;
    }

    .cat-card:hover .cat-icon {
      background: rgba(210,237,5,0.14);
      border-color: rgba(210,237,5,0.4);
    }

    .cat-card:hover .cat-icon i { transform: scale(1.15); }

    .cat-title {
      font-family: 'Orbitron', sans-serif;
      font-size: 0.72rem;
      letter-spacing: 0.16em;
      color: #fff;
      text-transform: uppercase;
    }

    .cat-desc {
      font-size: 0.6rem;
      color: rgba(255,255,255,0.3);
      letter-spacing: 0.06em;
      line-height: 1.7;
    }

    .cat-cta {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.58rem;
      letter-spacing: 0.14em;
      color: var(--accent);
      opacity: 0;
      transform: translateY(4px);
      transition: all .3s;
    }

    .cat-cta i { font-size: 0.65rem; }
    .cat-card:hover .cat-cta { opacity: 1; transform: translateY(0); }

    /* Botón CTA tienda */
    .shop-cta-wrap {
      display: flex;
      justify-content: center;
    }

    .btn-tienda {
      display: inline-flex;
      align-items: center;
      gap: 14px;
      padding: 20px 64px;
      border: 2px solid var(--accent);
      color: var(--accent);
      font-family: 'Orbitron', sans-serif;
      font-size: 0.85rem;
      font-weight: 700;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      text-decoration: none;
      border-radius: 3px;
      position: relative;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-tienda::before {
      content: '';
      position: absolute;
      inset: 0;
      background: var(--accent);
      transform: scaleY(0);
      transform-origin: bottom;
      transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      z-index: 0;
    }

    .btn-tienda:hover::before { transform: scaleY(1); }
    .btn-tienda:hover { color: #000; box-shadow: 0 20px 60px rgba(210,237,5,0.2); }
    .btn-tienda i, .btn-tienda span { position: relative; z-index: 1; }
    .btn-tienda i { font-size: 1.1rem; }

    .btn-badge {
      position: relative; z-index: 1;
      background: rgba(210,237,5,0.12);
      border: 1px solid rgba(210,237,5,0.25);
      border-radius: 20px;
      padding: 3px 12px;
      font-size: 0.55rem;
      letter-spacing: 0.1em;
      transition: all .3s;
    }

    .btn-tienda:hover .btn-badge {
      background: rgba(0,0,0,0.15);
      border-color: rgba(0,0,0,0.2);
    }

    /* ══════════════════════════════════════════════════════
       SECCIÓN SERVICIOS
    ══════════════════════════════════════════════════════ */
    .servicios-section {
      padding: 100px 50px;
      text-align: center;
      background-image:
        linear-gradient(rgba(0,0,0,0.62), rgba(0,0,0,0.62)),
        url('./images/imagen_servicios.jpg');
      background-position: center;
      background-size: cover;
      background-attachment: fixed;
      background-repeat: no-repeat;
      position: relative;
    }

    .servicios-section .section-tag { justify-content: center; margin-bottom: 12px; }

    .servicios-section h2 {
      font-family: 'Orbitron', sans-serif;
      font-size: clamp(1.7rem, 3.8vw, 4rem);
      font-weight: 400;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.35);
      margin-bottom: 14px;
    }

    .servicios-section .section-sub {
      font-size: 0.65rem;
      color: var(--muted);
      letter-spacing: 0.1em;
      margin-bottom: 56px;
    }

    .servicios-grid {
      display: flex;
      justify-content: center;
      gap: 24px;
      flex-wrap: wrap;
    }

    .servicio-card {
      position: relative;
      width: 300px; height: 400px;
      perspective: 1000px;
    }

    .front, .back {
      position: absolute;
      inset: 0;
      backface-visibility: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 12px;
      transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .front { overflow: hidden; background: #1a1a1a; }
    .front img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }

    .back {
      font-family: 'Audiowide', sans-serif;
      background: #131313;
      border: 1px solid rgba(210,237,5,0.12);
      color: rgba(210,210,200,0.85);
      padding: 28px 24px;
      text-align: left;
      transform: rotateY(180deg);
      flex-direction: column;
      justify-content: flex-start;
      font-size: 0.7rem;
      line-height: 1.8;
      gap: 12px;
    }

    .back-title {
      font-family: 'Orbitron', sans-serif;
      font-size: 0.7rem;
      color: var(--accent);
      letter-spacing: 0.15em;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .back-title i { font-size: 0.85rem; }

    .back ol { margin: 0; padding-left: 18px; list-style-type: decimal; }
    .back li  { margin-bottom: 7px; }

    .servicio-card:hover .front { transform: rotateY(180deg); }
    .servicio-card:hover .back  { transform: rotateY(0); }

    /* ══════════════════════════════════════════════════════
       SECCIÓN CONTACTO + MAPA
    ══════════════════════════════════════════════════════ */
    .contacto-section {
      background: var(--bg);
      padding: 100px 0 0;
      position: relative;
      overflow: hidden;
    }

    .contacto-section::before {
      content: '';
      position: absolute;
      top: 0; left: 50%; transform: translateX(-50%);
      width: 1px; height: 60px;
      background: linear-gradient(to bottom, transparent, rgba(210,237,5,0.4));
    }

    .contacto-inner {
      max-width: 1300px;
      margin: 0 auto;
      padding: 0 60px;
    }

    .contacto-header {
      text-align: center;
      margin-bottom: 64px;
    }

    .contacto-header h2 {
      font-family: 'Orbitron', sans-serif;
      font-size: clamp(1.7rem, 3.5vw, 2.8rem);
      font-weight: 400;
      letter-spacing: 0.12em;
      color: #fff;
      margin-bottom: 12px;
    }

    .contacto-header h2 span { color: var(--accent); }
    .contacto-header p { font-size: 0.68rem; color: var(--muted); letter-spacing: 0.1em; line-height: 1.9; }

    .contacto-grid {
      display: grid;
      grid-template-columns: 1fr 1.55fr;
      gap: 0;
      border: 1px solid var(--border);
      border-radius: 16px 16px 0 0;
      overflow: hidden;
    }

    /* ── Panel info ── */
    .contacto-info {
      background: var(--surface);
      padding: 52px 44px;
      display: flex;
      flex-direction: column;
      border-right: 1px solid var(--border);
    }

    .info-label {
      font-family: 'Orbitron', sans-serif;
      font-size: 0.55rem;
      letter-spacing: 0.3em;
      color: rgba(210,237,5,0.45);
      text-transform: uppercase;
      margin-bottom: 28px;
    }

    .contact-item {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      padding: 20px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      text-decoration: none;
      color: inherit;
      transition: all .2s;
    }

    .contact-item:last-of-type { border-bottom: none; }

    .contact-item:hover .ci-icon {
      background: rgba(210,237,5,0.14);
      border-color: rgba(210,237,5,0.4);
    }

    .contact-item:hover .ci-label { color: var(--accent); }

    .ci-icon {
      width: 44px; height: 44px;
      background: rgba(210,237,5,0.06);
      border: 1px solid rgba(210,237,5,0.14);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      transition: all .25s;
    }

    .ci-icon i { font-size: 1.05rem; color: var(--accent); }

    .ci-key {
      font-size: 0.52rem;
      letter-spacing: 0.22em;
      color: rgba(255,255,255,0.28);
      text-transform: uppercase;
      margin-bottom: 4px;
      display: block;
    }

    .ci-label {
      font-family: 'Orbitron', sans-serif;
      font-size: 0.82rem;
      color: #fff;
      letter-spacing: 0.05em;
      transition: color .2s;
      display: block;
      line-height: 1.4;
    }

    .ci-sub {
      font-size: 0.58rem;
      color: rgba(255,255,255,0.28);
      letter-spacing: 0.07em;
      margin-top: 2px;
      display: block;
    }

    /* Horario */
    .horario-block {
      margin-top: 22px;
      background: rgba(210,237,5,0.04);
      border: 1px solid rgba(210,237,5,0.1);
      border-radius: 10px;
      padding: 18px 20px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .horario-row {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.62rem;
      letter-spacing: 0.07em;
    }

    .horario-row i { font-size: 0.75rem; flex-shrink: 0; }
    .horario-row .dia { color: rgba(255,255,255,0.38); flex: 1; }
    .horario-row .horas { font-family: 'Orbitron', sans-serif; font-size: 0.58rem; color: #fff; }
    .horario-row .horas.open  { color: var(--accent); }
    .horario-row .horas.close { color: rgba(255,80,80,0.75); }
    .h-divider { height: 1px; background: rgba(255,255,255,0.05); }

    /* Botón WhatsApp */
    .btn-whatsapp {
      margin-top: 28px;
      display: flex;
      align-items: center;
      gap: 13px;
      padding: 17px 26px;
      background: #25D366;
      color: #fff;
      font-family: 'Orbitron', sans-serif;
      font-size: 0.73rem;
      font-weight: 700;
      letter-spacing: 0.13em;
      text-decoration: none;
      border-radius: 10px;
      position: relative;
      overflow: hidden;
      transition: all .35s cubic-bezier(0.16,1,0.3,1);
      box-shadow: 0 8px 32px rgba(37,211,102,0.28);
    }

    .btn-whatsapp::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 60%);
      pointer-events: none;
    }

    .btn-whatsapp:hover {
      transform: translateY(-3px);
      box-shadow: 0 16px 48px rgba(37,211,102,0.42);
      background: #1eb858;
    }

    .btn-whatsapp i { font-size: 1.25rem; flex-shrink: 0; }

    .wa-text { display: flex; flex-direction: column; gap: 2px; }
    .wa-main { font-size: 0.73rem; }
    .wa-sub  { font-size: 0.52rem; font-weight: 400; opacity: .78; letter-spacing: 0.09em; }

    .wa-dot-wrap {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.52rem;
      opacity: .8;
    }

    .wa-dot {
      width: 7px; height: 7px;
      background: #fff;
      border-radius: 50%;
      animation: waPulse 2s ease-in-out infinite;
    }

    @keyframes waPulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%       { opacity: .45; transform: scale(.75); }
    }

    /* ── Panel mapa ── */
    .contacto-mapa {
      background: #0d0d0d;
      display: flex;
      flex-direction: column;
      min-height: 580px;
    }

    .mapa-header {
      padding: 22px 30px;
      background: rgba(10,10,10,0.96);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
      flex-shrink: 0;
    }

    .mapa-loc {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .mapa-loc i { font-size: 1.15rem; color: var(--accent); }

    .mapa-loc-name {
      font-family: 'Orbitron', sans-serif;
      font-size: 0.73rem;
      color: #fff;
      letter-spacing: 0.1em;
      display: block;
    }

    .mapa-loc-addr {
      font-size: 0.58rem;
      color: rgba(255,255,255,0.32);
      letter-spacing: 0.07em;
      display: block;
      margin-top: 2px;
    }

    .mapa-btns { display: flex; gap: 8px; }

    .mapa-btn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 16px;
      font-family: 'Audiowide', sans-serif;
      font-size: 0.58rem;
      letter-spacing: 0.1em;
      border-radius: 6px;
      text-decoration: none;
      border: 1px solid;
      transition: all .2s;
    }

    .mapa-btn i { font-size: 0.8rem; }

    .mapa-btn-solid {
      background: var(--accent);
      color: #000;
      border-color: var(--accent);
    }

    .mapa-btn-solid:hover {
      background: var(--accent2);
      box-shadow: 0 4px 20px rgba(210,237,5,0.3);
      transform: translateY(-1px);
    }

    .mapa-btn-ghost {
      background: transparent;
      color: rgba(255,255,255,0.45);
      border-color: rgba(255,255,255,0.1);
    }

    .mapa-btn-ghost:hover {
      border-color: rgba(210,237,5,0.3);
      color: var(--accent);
    }

    .mapa-frame-wrap {
      flex: 1;
      position: relative;
      min-height: 490px;
    }

    .mapa-frame-wrap::after {
      content: '';
      position: absolute;
      inset: 0;
      pointer-events: none;
      box-shadow: inset 0 0 80px rgba(0,0,0,0.35);
      z-index: 1;
    }

    .mapa-frame-wrap iframe {
      width: 100%; height: 100%;
      border: none;
      display: block;
      min-height: 490px;
      filter: grayscale(15%) brightness(0.88) contrast(1.05) saturate(0.9);
      transition: filter .4s;
    }

    .mapa-frame-wrap:hover iframe {
      filter: grayscale(0%) brightness(1) contrast(1) saturate(1);
    }

    /* ── Barra inferior redes ── */
    .contacto-bar {
      border-top: 1px solid var(--border);
      background: var(--surface);
    }

    .contacto-bar-inner {
      max-width: 1300px;
      margin: 0 auto;
      padding: 22px 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 14px;
    }

    .bar-copy {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.6rem;
      color: rgba(255,255,255,0.28);
      letter-spacing: 0.1em;
    }

    .bar-copy i { color: var(--accent); font-size: 0.8rem; }

    .social-bar {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .social-link {
      width: 38px; height: 38px;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,0.38);
      text-decoration: none;
      font-size: 0.95rem;
      transition: all .25s;
    }

    .social-link:hover {
      border-color: rgba(210,237,5,0.35);
      color: var(--accent);
      background: rgba(210,237,5,0.06);
      transform: translateY(-2px);
    }

    .social-link.wa {
      color: rgba(37,211,102,0.6);
      border-color: rgba(37,211,102,0.18);
    }

    .social-link.wa:hover {
      color: #25D366;
      border-color: rgba(37,211,102,0.5);
      background: rgba(37,211,102,0.06);
    }

    /* ══════════════════════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════════════════════ */
    @media (max-width: 1100px) {
      .cat-grid { grid-template-columns: repeat(2, 1fr); }
      .tienda-section { padding: 80px 36px; }
    }

    @media (max-width: 860px) {
      .contacto-grid { grid-template-columns: 1fr; border-radius: 12px 12px 0 0; }
      .contacto-info { border-right: none; border-bottom: 1px solid var(--border); padding: 36px 28px; }
      .contacto-mapa { min-height: 400px; }
      .mapa-frame-wrap { min-height: 360px; }
      .mapa-frame-wrap iframe { min-height: 360px; }
      .mapa-header { padding: 16px 20px; }
      .contacto-inner { padding: 0 20px; }
      .contacto-bar-inner { padding: 18px 20px; flex-direction: column; text-align: center; }
    }

    @media (max-width: 768px) {
      .tienda-section { padding: 70px 20px; }
      .servicios-section { padding: 70px 20px; }
      .cat-grid { gap: 12px; }
      .servicio-card { width: 260px; height: 360px; }
      .seo-band { padding: 16px 20px; }
      .contacto-section { padding: 70px 0 0; }
      .btn-tienda { padding: 16px 36px; font-size: 0.75rem; }
    }

    @media (max-width: 520px) {
      .cat-grid { grid-template-columns: 1fr 1fr; }
      .cat-card { padding: 26px 16px; }
      .mapa-btns { flex-direction: column; width: 100%; }
      .mapa-btn { justify-content: center; }
      .wa-dot-wrap { display: none; }
      .btn-hero { padding: 15px 32px; }
      .hero-logo { max-width: 220px; }
    }
  </style>
</head>
<body>

  <?php include './includes/menu.php'; ?>

  <!-- ══════════════════════════════════════════════════
       HERO
  ══════════════════════════════════════════════════ -->
  <section class="hero-section" aria-label="VP Motos — Repuestos y Accesorios en Quito Ecuador">

    <video class="hero-video" autoplay muted loop playsinline preload="auto">
      <source src="./images/Motorcycle_Cinematic_Video.mp4" type="video/mp4">
    </video>

    <div class="hero-overlay" aria-hidden="true"></div>

    <!-- Logo + botón -->
    <div class="hero-center" id="heroCenter">

      <!-- Logo: width/height AUTO para que nunca se deforme -->
      <img
        src="./images/logo_vp.png"
        alt="VP Motos — Repuestos y Accesorios para Motocicletas en Ecuador"
        class="hero-logo"
        fetchpriority="high"
        draggable="false">

      <div class="hero-cta-wrap">
        <a href="tienda.php" class="btn-hero" aria-label="Ir a la tienda online de VP Motos">
          <i class="bi bi-bag-check-fill" aria-hidden="true"></i>
          <span>Visitar Tienda Online</span>
          <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
        <p class="hero-sub">
          <span>Repuestos</span>
          <span class="hero-sub-dot" aria-hidden="true"></span>
          <span>Accesorios</span>
          <span class="hero-sub-dot" aria-hidden="true"></span>
          <span>Llantas</span>
          <span class="hero-sub-dot" aria-hidden="true"></span>
          <span>Aceites</span>
        </p>
      </div>

    </div>

    <!-- Scroll hint -->
    <div class="hero-scroll" aria-hidden="true">
      <span>EXPLORAR</span>
      <i class="bi bi-chevron-down"></i>
    </div>

    <!-- Carrusel marcas -->
    <div class="carousel-strip" aria-label="Marcas disponibles: Yamaha, Bajaj, Honda, KTM y más">
      <div class="carousel-track">
        <div class="carousel-item"><img src="./images/yam.jpg"       alt="Yamaha Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/bajaj1.JPG"    alt="Bajaj Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/Honda.png"     alt="Honda Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/ktm1.JPG"      alt="KTM Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/cfmoto.webp"   alt="CFMoto Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/Suzuki.webp"   alt="Suzuki Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/Shineray.png"  alt="Shineray Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/tuko.png"      alt="Tuko Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/benelli2.jpeg" alt="Benelli Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/Kawasaki.jpeg" alt="Kawasaki Ecuador" loading="lazy"></div>
        <!-- clon loop -->
        <div class="carousel-item"><img src="./images/yam.jpg"       alt="Yamaha Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/bajaj1.JPG"    alt="Bajaj Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/Honda.png"     alt="Honda Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/ktm1.JPG"      alt="KTM Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/cfmoto.webp"   alt="CFMoto Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/Suzuki.webp"   alt="Suzuki Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/Shineray.png"  alt="Shineray Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/tuko.png"      alt="Tuko Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/benelli2.jpeg" alt="Benelli Ecuador" loading="lazy"></div>
        <div class="carousel-item"><img src="./images/Kawasaki.jpeg" alt="Kawasaki Ecuador" loading="lazy"></div>
      </div>
    </div>

  </section>

  <!-- ══════════════════════════════════════════════════
       BANDA SEO
  ══════════════════════════════════════════════════ -->
  <div class="seo-band" aria-hidden="true">
    <p>
      <strong>VP Motos Quito</strong> — Tu tienda de confianza para
      <strong>repuestos originales de moto en Ecuador</strong>,
      accesorios para motocicletas, llantas, aceites y lubricantes.
      Marcas: <strong>Yamaha, Honda, Bajaj, KTM, Kawasaki, Suzuki, CFMoto, Benelli</strong>.
      Envíos a todo Ecuador. Servicio técnico especializado en Quito.
    </p>
  </div>

  <!-- ══════════════════════════════════════════════════
       TIENDA — CATEGORÍAS
  ══════════════════════════════════════════════════ -->
  <section class="tienda-section" id="tienda" aria-label="Categorías de productos VP Motos">
    <div class="section-header">
      <div class="section-tag">
        <i class="bi bi-shop" aria-hidden="true"></i>
        Tienda Online · Quito, Ecuador
      </div>
      <h2>Todo para tu <em>Moto</em></h2>
      <p>Repuestos originales, accesorios y más. Stock disponible con envío a todo Ecuador.</p>
    </div>

    <div class="cat-grid">
      <a href="tienda.php?categoria=Repuestos" class="cat-card" aria-label="Ver repuestos originales">
        <div class="cat-icon"><i class="bi bi-gear-fill" aria-hidden="true"></i></div>
        <div class="cat-title">Repuestos Originales</div>
        <div class="cat-desc">Honda, Yamaha, Bajaj, KTM y más marcas líderes</div>
        <div class="cat-cta"><i class="bi bi-arrow-right" aria-hidden="true"></i> Ver productos</div>
      </a>

      <a href="tienda.php?categoria=Accesorios" class="cat-card" aria-label="Ver accesorios">
        <div class="cat-icon"><i class="bi bi-shield-check" aria-hidden="true"></i></div>
        <div class="cat-title">Accesorios</div>
        <div class="cat-desc">Equipamiento, protección y personalización</div>
        <div class="cat-cta"><i class="bi bi-arrow-right" aria-hidden="true"></i> Ver productos</div>
      </a>

      <a href="tienda.php?categoria=Llantas" class="cat-card" aria-label="Ver llantas">
        <div class="cat-icon"><i class="bi bi-circle" aria-hidden="true"></i></div>
        <div class="cat-title">Llantas</div>
        <div class="cat-desc">Todas las medidas y marcas para Ecuador</div>
        <div class="cat-cta"><i class="bi bi-arrow-right" aria-hidden="true"></i> Ver productos</div>
      </a>

      <a href="tienda.php?categoria=Aceites" class="cat-card" aria-label="Ver aceites y lubricantes">
        <div class="cat-icon"><i class="bi bi-droplet-fill" aria-hidden="true"></i></div>
        <div class="cat-title">Aceites &amp; Lubricantes</div>
        <div class="cat-desc">Motul, Castrol, Amsoil y más marcas certificadas</div>
        <div class="cat-cta"><i class="bi bi-arrow-right" aria-hidden="true"></i> Ver productos</div>
      </a>
    </div>

    <div class="shop-cta-wrap">
      <a href="tienda.php" class="btn-tienda" aria-label="Ver todos los productos">
        <i class="bi bi-grid-3x3-gap-fill" aria-hidden="true"></i>
        <span>Ver Todos los Productos</span>
        <span class="btn-badge">TIENDA ONLINE</span>
      </a>
    </div>
  </section>

  <!-- ══════════════════════════════════════════════════
       SERVICIOS
  ══════════════════════════════════════════════════ -->
  <section class="servicios-section" id="quienes-somos" aria-label="Servicios VP Motos Quito">

    <div class="section-tag" style="justify-content:center; margin-bottom:12px;">
      <i class="bi bi-wrench-adjustable-circle-fill" aria-hidden="true"></i>
      Servicio Técnico Especializado
    </div>

    <h2>Servicios que Ofrecemos</h2>
    <p class="section-sub">Mantenimiento profesional para tu motocicleta en Quito</p>

    <div class="servicios-grid">

      <div class="servicio-card">
        <div class="front">
          <img src="./images/ABC.png" alt="Servicio ABC mantenimiento moto Quito">
        </div>
        <div class="back">
          <div class="back-title">
            <i class="bi bi-check2-circle" aria-hidden="true"></i>
            Servicio ABC
          </div>
          <ol>
            <li>Ajuste de presión de neumáticos</li>
            <li>Limpieza o cambio de bujías</li>
            <li>Limpieza, lubricación y ajuste de cadena</li>
            <li>Reajuste de tornillería principal</li>
            <li>Revisión y calibración de luces</li>
            <li>Lubricación de maniguetas</li>
            <li>Lubricación de cables (freno-embrague)</li>
            <li>Limpieza del drenaje del silenciador</li>
          </ol>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="./images/GASES.png" alt="Revisión de gases moto Quito">
        </div>
        <div class="back">
          <div class="back-title">
            <i class="bi bi-wind" aria-hidden="true"></i>
            Revisión de Gases
          </div>
          <ol>
            <li>Diagnóstico de emisiones</li>
            <li>Ajuste de mezcla aire-combustible</li>
            <li>Revisión del sistema de escape</li>
            <li>Control de contaminación</li>
          </ol>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="./images/INYECTORES.png" alt="Limpieza de inyectores moto Ecuador">
        </div>
        <div class="back">
          <div class="back-title">
            <i class="bi bi-droplet" aria-hidden="true"></i>
            Limpieza de Inyectores
          </div>
          <ol>
            <li>Diagnóstico previo del sistema</li>
            <li>Limpieza ultrasónica de inyectores</li>
            <li>Calibración de caudal</li>
            <li>Prueba de atomización</li>
            <li>Reinstalación y ajuste final</li>
          </ol>
        </div>
      </div>

      <div class="servicio-card">
        <div class="front">
          <img src="./images/scaner.png" alt="Diagnóstico scanner moto Quito">
        </div>
        <div class="back">
          <div class="back-title">
            <i class="bi bi-cpu" aria-hidden="true"></i>
            Diagnóstico Scanner
          </div>
          <ol>
            <li>Lectura de códigos de falla</li>
            <li>Diagnóstico electrónico completo</li>
            <li>Revisión de sensores</li>
            <li>Informe técnico detallado</li>
            <li>Borrado de errores post-reparación</li>
          </ol>
        </div>
      </div>

    </div>
  </section>

  <!-- ══════════════════════════════════════════════════
       CONTACTO + MAPA
  ══════════════════════════════════════════════════ -->
  <section class="contacto-section" id="contactanos" aria-label="Contacto y ubicación VP Motos Quito">

    <div class="contacto-inner">

      <div class="contacto-header">
        <div class="section-tag" style="justify-content:center; margin-bottom:12px;">
          <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
          Encuéntranos en Quito
        </div>
        <h2>Visítanos o <span>Escríbenos</span></h2>
        <p>Estamos listos para atenderte. Escríbenos por WhatsApp o visítanos directamente en tienda.</p>
      </div>

      <div class="contacto-grid">

        <!-- Panel info -->
        <div class="contacto-info">
          <span class="info-label">Información de contacto</span>

          <a href="https://wa.me/593996628440?text=Hola%20VP%20Motos%2C%20me%20gustar%C3%ADa%20consultar%20sobre%20sus%20productos."
             class="contact-item" target="_blank" rel="noopener noreferrer"
             aria-label="Escribir a VP Motos por WhatsApp">
            <div class="ci-icon"><i class="bi bi-whatsapp" aria-hidden="true"></i></div>
            <div>
              <span class="ci-key">WhatsApp</span>
              <span class="ci-label">0996 628 440</span>
              <span class="ci-sub">Atención rápida en línea</span>
            </div>
          </a>

          <a href="tel:+593996628440" class="contact-item" aria-label="Llamar a VP Motos">
            <div class="ci-icon"><i class="bi bi-telephone-fill" aria-hidden="true"></i></div>
            <div>
              <span class="ci-key">Teléfono</span>
              <span class="ci-label">0996 628 440</span>
              <span class="ci-sub">Llámanos directamente</span>
            </div>
          </a>

          <a href="https://maps.google.com/?q=VP+MOTOS+Quito+Ecuador"
             target="_blank" rel="noopener noreferrer"
             class="contact-item" aria-label="Ver VP Motos en Google Maps">
            <div class="ci-icon"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></div>
            <div>
              <span class="ci-key">Ubicación</span>
              <span class="ci-label">VP Motos</span>
              <span class="ci-sub">Quito, Ecuador — Ver en Maps</span>
            </div>
          </a>

          <!-- Horario -->
          <div class="horario-block" aria-label="Horario de atención VP Motos">
            <div class="horario-row">
              <i class="bi bi-clock-fill" style="color:var(--accent);" aria-hidden="true"></i>
              <span class="dia">Lunes — Viernes</span>
              <span class="horas open">08:00 — 18:00</span>
            </div>
            <div class="h-divider"></div>
            <div class="horario-row">
              <i class="bi bi-clock" style="color:rgba(210,237,5,.4);" aria-hidden="true"></i>
              <span class="dia">Sábado</span>
              <span class="horas">08:00 — 14:00</span>
            </div>
            <div class="h-divider"></div>
            <div class="horario-row">
              <i class="bi bi-clock" style="color:rgba(255,80,80,.45);" aria-hidden="true"></i>
              <span class="dia">Domingo</span>
              <span class="horas close">Cerrado</span>
            </div>
          </div>

          <!-- Botón WhatsApp -->
          <a href="https://wa.me/593996628440?text=Hola%20VP%20Motos%2C%20me%20gustar%C3%ADa%20consultar%20sobre%20sus%20productos."
             class="btn-whatsapp"
             target="_blank" rel="noopener noreferrer"
             aria-label="Abrir WhatsApp con VP Motos">
            <i class="bi bi-whatsapp" aria-hidden="true"></i>
            <div class="wa-text">
              <span class="wa-main">Escribir por WhatsApp</span>
              <span class="wa-sub">0996 628 440 · Respuesta rápida</span>
            </div>
            <div class="wa-dot-wrap" aria-hidden="true">
              <div class="wa-dot"></div>
              En línea
            </div>
          </a>

        </div><!-- /contacto-info -->

        <!-- Panel mapa -->
        <div class="contacto-mapa">

          <div class="mapa-header">
            <div class="mapa-loc">
              <i class="bi bi-pin-map-fill" aria-hidden="true"></i>
              <div>
                <span class="mapa-loc-name">VP MOTOS</span>
                <span class="mapa-loc-addr">Quito, Pichincha, Ecuador</span>
              </div>
            </div>
            <div class="mapa-btns">
              <a href="https://maps.google.com/?q=VP+MOTOS+Quito+Ecuador"
                 target="_blank" rel="noopener noreferrer"
                 class="mapa-btn mapa-btn-solid"
                 aria-label="Cómo llegar a VP Motos">
                <i class="bi bi-signpost-split-fill" aria-hidden="true"></i>
                Cómo llegar
              </a>
              <a href="https://wa.me/593996628440?text=Hola%2C%20quisiera%20saber%20la%20direcci%C3%B3n%20exacta%20de%20VP%20Motos."
                 target="_blank" rel="noopener noreferrer"
                 class="mapa-btn mapa-btn-ghost"
                 aria-label="Pedir dirección por WhatsApp">
                <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
                Pedir dirección
              </a>
            </div>
          </div>

          <div class="mapa-frame-wrap">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d997.4542717024841!2d-78.28587749865932!3d-0.04505432680771296!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d5f4d1db332adb%3A0xf45038f13b5cde39!2sVP%20MOTOS!5e0!3m2!1ses!2sec!4v1771651566230!5m2!1ses!2sec"
              title="Ubicación de VP Motos en Quito, Ecuador"
              allowfullscreen
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              aria-label="Mapa Google Maps VP Motos Quito">
            </iframe>
          </div>

        </div><!-- /contacto-mapa -->

      </div><!-- /contacto-grid -->

    </div><!-- /contacto-inner -->

    <!-- Barra redes -->
    <div class="contacto-bar">
      <div class="contacto-bar-inner">
        <div class="bar-copy">
          <i class="bi bi-shield-check" aria-hidden="true"></i>
          VP Motos Quito &nbsp;·&nbsp; Repuestos originales garantizados &nbsp;·&nbsp; Envíos a todo Ecuador
        </div>
        <nav class="social-bar" aria-label="Redes sociales VP Motos">
          <a href="https://www.facebook.com/profile.php?id=61553909536855"
             target="_blank" rel="noopener noreferrer"
             class="social-link" aria-label="VP Motos en Facebook">
            <i class="bi bi-facebook" aria-hidden="true"></i>
          </a>
          <a href="https://www.tiktok.com/@vpmotos"
             target="_blank" rel="noopener noreferrer"
             class="social-link" aria-label="VP Motos en TikTok">
            <i class="bi bi-tiktok" aria-hidden="true"></i>
          </a>
          <a href="https://instagram.com/vpmotos"
             target="_blank" rel="noopener noreferrer"
             class="social-link" aria-label="VP Motos en Instagram">
            <i class="bi bi-instagram" aria-hidden="true"></i>
          </a>
          <a href="https://wa.me/593996628440"
             target="_blank" rel="noopener noreferrer"
             class="social-link wa" aria-label="VP Motos en WhatsApp">
            <i class="bi bi-whatsapp" aria-hidden="true"></i>
          </a>
        </nav>
      </div>
    </div>

  </section>

  <?php include('./includes/footer.php'); ?>

  <script src="./js/scripts.js"></script>
  <script>
    /* Logo hero: desvanece al hacer scroll */
    (function () {
      const center = document.getElementById('heroCenter');
      const hero   = document.querySelector('.hero-section');
      if (!center || !hero) return;

      window.addEventListener('scroll', function () {
        const ratio = Math.min(window.scrollY / (hero.offsetHeight * 0.38), 1);
        const e     = ratio * ratio;
        center.style.opacity   = String(1 - e);
        center.style.transform = 'translateY(' + (-e * 28) + 'px)';
        center.style.filter    = 'blur(' + (e * 4) + 'px)';
      }, { passive: true });
    })();
  </script>

</body>
</html>