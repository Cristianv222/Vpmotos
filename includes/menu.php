<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$_nav_cart_count = array_sum(array_column($_SESSION['carrito'] ?? [], 'cantidad'));
?>
<!DOCTYPE html>
<html lang="es-EC">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title>VP Motos | Repuestos y Accesorios para Motos en Ecuador</title>
    <meta name="description" content="En VP Motos encontrarás repuestos, accesorios y servicio técnico especializado para motocicletas en Ecuador.">
    <meta name="keywords" content="VP Motos, repuestos motos Ecuador, accesorios motocicletas, servicio técnico motos, tienda motos Ecuador">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="https://www.vpmotos.ec/">
    <meta name="geo.region" content="EC">
    <meta name="geo.placename" content="Ecuador">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.vpmotos.ec/">
    <meta property="og:title" content="VP Motos | Repuestos y Accesorios para Motos en Ecuador">
    <meta property="og:description" content="En VP Motos encontrarás repuestos, accesorios y servicio técnico especializado para motocicletas en Ecuador.">
    <meta property="og:image" content="https://www.vpmotos.ec/images/logo_vp.png">
    <meta property="og:locale" content="es_EC">
    <meta property="og:site_name" content="VP Motos">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="VP Motos | Repuestos y Accesorios para Motos en Ecuador">
    <meta name="twitter:description" content="Repuestos originales, accesorios, llantas y aceites para motos en Ecuador.">
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
      "description": "Tienda especializada en repuestos originales, accesorios, llantas y aceites para motocicletas en Ecuador.",
      "url": "https://www.vpmotos.ec",
      "logo": { "@type": "ImageObject", "url": "https://www.vpmotos.ec/images/logo_vp.png" },
      "telephone": "+593996628440",
      "address": { "@type": "PostalAddress", "addressLocality": "Quito", "addressRegion": "Pichincha", "addressCountry": "EC" },
      "areaServed": { "@type": "Country", "name": "Ecuador" },
      "sameAs": [
        "https://www.facebook.com/profile.php?id=61553909536855",
        "https://www.tiktok.com/@vpmotos",
        "https://www.instagram.com/vpmotos"
      ]
    }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<style>
/* ══════════════════════════════════════════════════════
   RESET
══════════════════════════════════════════════════════ */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

:root {
    --accent:   #D2ED05;
    --accent2:  #a8bc04;
    --dark:     rgba(34,34,34,0.88);
}

/* ══════════════════════════════════════════════════════
   NAVBAR DESKTOP
══════════════════════════════════════════════════════ */
.navbar {
    position: fixed; top: 0; left: 0; width: 100%;
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 80px;
    background: transparent;
    z-index: 900;
    transition: all 0.3s ease;
}

.navbar.scrolled {
    background: var(--dark);
    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);
    box-shadow: 0 2px 12px rgba(0,0,0,0.4);
    padding: 10px 80px;
}

/* Logo */
.logo a { display: flex; align-items: center; }
.logo img {
    height: 150px;
    width: auto;
    object-fit: contain;
    cursor: pointer;
    transition: height .3s;
}
.navbar.scrolled .logo img { height: 80px; }

/* Nav links */
.nav-links {
    display: flex;
    align-items: center;
    gap: 32px;
}

.nav-links a {
    text-decoration: none;
    color: #fff;
    font-family: 'Audiowide', sans-serif;
    font-size: 0.88rem;
    font-weight: 500;
    letter-spacing: 0.05em;
    transition: color .25s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.navbar.scrolled .nav-links a { color: var(--accent); }
.nav-links a:hover { color: var(--accent); }
.nav-links a i { font-size: 0.85rem; }

/* Separador vertical */
.v-line {
    height: 28px; width: 1px;
    background: rgba(66,218,6,0.8);
    margin: 0 14px;
    transition: background .3s;
}
.navbar.scrolled .v-line { background: #96e948; }

/* Nav right */
.nav-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Iconos sociales */
.nav-social {
    display: flex;
    gap: 12px;
    align-items: center;
}

.nav-social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.7);
    font-size: 0.95rem;
    text-decoration: none;
    transition: all .25s;
}

.nav-social a:hover {
    border-color: rgba(210,237,5,0.5);
    color: var(--accent);
    background: rgba(210,237,5,0.08);
    transform: translateY(-2px);
}

.navbar.scrolled .nav-social a {
    border-color: rgba(210,237,5,0.2);
    color: rgba(210,237,5,0.6);
}

/* Botón carrito */
.nav-cart {
    position: relative;
    display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px;
    border-radius: 50%;
    background: rgba(210,237,5,0.08);
    border: 1px solid rgba(210,237,5,0.3);
    color: var(--accent);
    text-decoration: none;
    font-size: 1rem;
    transition: all .2s;
}

.nav-cart:hover {
    background: rgba(210,237,5,0.18);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(210,237,5,0.2);
}

.nav-cart-badge {
    position: absolute; top: -5px; right: -5px;
    background: var(--accent); color: #000;
    font-size: 0.52rem;
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    width: 18px; height: 18px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

/* ══════════════════════════════════════════════════════
   HAMBURGER
══════════════════════════════════════════════════════ */
.hamburger {
    display: none;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    cursor: pointer;
    width: 36px; height: 36px;
    padding: 4px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.15);
    background: transparent;          /* ← fix: elimina el blanco del <button> */
    -webkit-appearance: none;
    appearance: none;
    transition: all .25s;
    z-index: 950;
}

.hamburger:hover {
    border-color: rgba(210,237,5,0.4);
    background: rgba(210,237,5,0.06);
}

.hamburger span {
    display: block;
    width: 100%; height: 2px;
    background: #fff;
    border-radius: 2px;
    transition: all .3s ease;
}

.navbar.scrolled .hamburger span { background: var(--accent); }
.hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
.hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ══════════════════════════════════════════════════════
   MENÚ MÓVIL
══════════════════════════════════════════════════════ */
.mobile-menu {
    position: fixed;
    inset: 0;
    z-index: 1100;
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.38s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none;
}

.mobile-menu.active {
    transform: translateX(0);
    pointer-events: all;
}

/* Fondo con glassmorphism oscuro */
.mm-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(6,6,6,0.97);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
}

/* Contenido del menú */
.mm-content {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    padding: 0;
    overflow-y: auto;
}

/* Header del menú móvil */
.mm-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}

.mm-logo img {
    height: 48px;
    width: auto;
    object-fit: contain;
}

.mm-close {
    width: 38px; height: 38px;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px;
    background: transparent;
    color: rgba(255,255,255,0.6);
    font-size: 1.1rem;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
}

.mm-close:hover {
    border-color: rgba(210,237,5,0.4);
    color: var(--accent);
    background: rgba(210,237,5,0.06);
}

/* ── BOTÓN TIENDA DESTACADO ── */
.mm-shop-btn {
    margin: 24px 24px 8px;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 22px;
    background: var(--accent);
    color: #000;
    text-decoration: none;
    border-radius: 12px;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    position: relative;
    overflow: hidden;
    transition: all .3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 8px 32px rgba(210,237,5,0.35);
    flex-shrink: 0;
}

.mm-shop-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
    pointer-events: none;
}

.mm-shop-btn:active {
    transform: scale(0.98);
    box-shadow: 0 4px 16px rgba(210,237,5,0.3);
}

.mm-shop-icon {
    width: 42px; height: 42px;
    background: rgba(0,0,0,0.12);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.mm-shop-icon i { font-size: 1.3rem; }

.mm-shop-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.mm-shop-main { font-size: 0.82rem; display: block; }
.mm-shop-sub  { font-size: 0.55rem; font-weight: 400; opacity: .75; letter-spacing: 0.1em; display: block; }

.mm-shop-arrow { font-size: 1rem; opacity: .7; flex-shrink: 0; }

/* ── Separador con label ── */
.mm-sep {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 24px;
    margin: 16px 0 8px;
}

.mm-sep-line { flex: 1; height: 1px; background: rgba(255,255,255,0.06); }

.mm-sep-label {
    font-size: 0.52rem;
    letter-spacing: 0.25em;
    color: rgba(255,255,255,0.25);
    text-transform: uppercase;
    white-space: nowrap;
}

/* ── Links de navegación ── */
.mm-nav {
    display: flex;
    flex-direction: column;
    padding: 0 16px;
    gap: 4px;
    flex: 1;
}

.mm-link {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 16px;
    border-radius: 10px;
    text-decoration: none;
    color: rgba(255,255,255,0.7);
    font-family: 'Audiowide', sans-serif;
    font-size: 0.82rem;
    letter-spacing: 0.06em;
    border: 1px solid transparent;
    transition: all .2s;
    position: relative;
}

.mm-link:hover, .mm-link:active {
    background: rgba(210,237,5,0.06);
    border-color: rgba(210,237,5,0.15);
    color: #fff;
}

.mm-link i {
    width: 20px;
    font-size: 1rem;
    color: rgba(210,237,5,0.6);
    flex-shrink: 0;
    text-align: center;
}

/* Carrito en móvil con badge integrado */
.mm-link-cart {
    justify-content: space-between;
}

.mm-cart-right {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: auto;
}

.mm-cart-badge {
    background: var(--accent);
    color: #000;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.05em;
}

.mm-cart-chevron {
    color: rgba(255,255,255,0.25);
    font-size: 0.75rem;
}

/* ── Footer del menú ── */
.mm-footer {
    padding: 20px 24px;
    border-top: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}

.mm-footer-title {
    font-size: 0.52rem;
    letter-spacing: 0.25em;
    color: rgba(255,255,255,0.25);
    text-transform: uppercase;
    margin-bottom: 14px;
}

/* Redes sociales en móvil */
.mm-social {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.mm-social a {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 11px 8px;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 10px;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    font-size: 1rem;
    transition: all .2s;
}

.mm-social a:hover {
    border-color: rgba(210,237,5,0.3);
    color: var(--accent);
    background: rgba(210,237,5,0.05);
}

/* Botón WhatsApp en menú móvil */
.mm-whatsapp {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    background: rgba(37,211,102,0.1);
    border: 1px solid rgba(37,211,102,0.25);
    border-radius: 10px;
    color: #25D366;
    text-decoration: none;
    font-size: 0.72rem;
    letter-spacing: 0.06em;
    transition: all .25s;
}

.mm-whatsapp:hover {
    background: rgba(37,211,102,0.18);
    border-color: rgba(37,211,102,0.5);
}

.mm-whatsapp i { font-size: 1.15rem; }

.mm-whatsapp-text { display: flex; flex-direction: column; gap: 1px; }
.mm-whatsapp-num { font-family: 'Orbitron', sans-serif; font-size: 0.7rem; color: #fff; }
.mm-whatsapp-sub { font-size: 0.55rem; color: rgba(37,211,102,0.7); letter-spacing: 0.08em; }

/* ══════════════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════════════ */
@media screen and (max-width: 900px) {
    .navbar { padding: 14px 20px; }
    .navbar.scrolled { padding: 10px 20px; }
    .nav-links, .nav-right { display: none; }
    .hamburger { display: flex; }
    .logo img { height: 72px; }
    .navbar.scrolled .logo img { height: 56px; }
}
</style>

<!-- ══════════════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════════════ -->
<nav class="navbar" id="navbar" role="navigation" aria-label="Menú principal">

    <!-- Logo -->
    <div class="logo">
        <a href="index.php" title="VP Motos — Inicio" aria-label="Ir al inicio VP Motos">
            <img src="./images/logo_vp.png"
                 alt="VP Motos — Repuestos y Accesorios para Motos en Ecuador"
                 width="150" height="150"
                 loading="eager" fetchpriority="high">
        </a>
    </div>

    <!-- Links desktop -->
    <div class="nav-links">
        <a href="index.php" title="Inicio">
            <i class="bi bi-house-fill" aria-hidden="true"></i> Inicio
        </a>
        <a href="tienda.php" title="Tienda de repuestos y accesorios">
            <i class="bi bi-bag-fill" aria-hidden="true"></i> Tienda
        </a>
        <a href="index.php#contactanos" title="Contáctanos">
            <i class="bi bi-chat-dots-fill" aria-hidden="true"></i> Contáctanos
        </a>
    </div>

    <!-- Derecha desktop -->
    <div class="nav-right">
        <div class="v-line" aria-hidden="true"></div>

        <!-- Sociales -->
        <nav class="nav-social" aria-label="Redes sociales">
            <a href="https://www.facebook.com/profile.php?id=61553909536855"
               target="_blank" rel="noopener noreferrer"
               title="VP Motos en Facebook" aria-label="Facebook VP Motos">
                <i class="bi bi-facebook" aria-hidden="true"></i>
            </a>
            <a href="https://www.tiktok.com/@vpmotos"
               target="_blank" rel="noopener noreferrer"
               title="VP Motos en TikTok" aria-label="TikTok VP Motos">
                <i class="bi bi-tiktok" aria-hidden="true"></i>
            </a>
            <a href="https://instagram.com/vpmotos"
               target="_blank" rel="noopener noreferrer"
               title="VP Motos en Instagram" aria-label="Instagram VP Motos">
                <i class="bi bi-instagram" aria-hidden="true"></i>
            </a>
        </nav>

        <!-- Carrito -->
        <a href="cart.php" class="nav-cart" id="nav-cart-btn"
           title="Ver carrito" aria-label="Carrito de compras">
            <i class="bi bi-bag-check" aria-hidden="true"></i>
            <span class="nav-cart-badge" id="nav-cart-badge"
                  style="<?= $_nav_cart_count > 0 ? '' : 'display:none' ?>">
                <?= $_nav_cart_count ?>
            </span>
        </a>
    </div>

    <!-- Hamburger -->
    <button class="hamburger" id="hamburger"
            aria-label="Abrir menú" aria-expanded="false" aria-controls="mobile-menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

</nav>

<!-- ══════════════════════════════════════════════════
     MENÚ MÓVIL
══════════════════════════════════════════════════ -->
<div class="mobile-menu" id="mobile-menu"
     role="dialog" aria-modal="true" aria-label="Menú de navegación">

    <!-- Fondo -->
    <div class="mm-backdrop" id="mm-backdrop"></div>

    <!-- Contenido -->
    <div class="mm-content">

        <!-- Header -->
        <div class="mm-header">
            <div class="mm-logo">
                <a href="index.php" aria-label="Inicio VP Motos">
                    <img src="./images/logo_vp.png" alt="VP Motos" width="48" height="48">
                </a>
            </div>
            <button class="mm-close" id="mm-close" aria-label="Cerrar menú">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <!-- ★ BOTÓN TIENDA DESTACADO ★ -->
        <a href="tienda.php" class="mm-shop-btn" aria-label="Ir a la tienda online VP Motos">
            <div class="mm-shop-icon">
                <i class="bi bi-bag-check-fill" aria-hidden="true"></i>
            </div>
            <div class="mm-shop-text">
                <span class="mm-shop-main">Tienda Online</span>
                <span class="mm-shop-sub">Repuestos · Accesorios · Llantas · Aceites</span>
            </div>
            <i class="bi bi-arrow-right mm-shop-arrow" aria-hidden="true"></i>
        </a>

        <!-- Separador -->
        <div class="mm-sep">
            <div class="mm-sep-line"></div>
            <span class="mm-sep-label">Navegación</span>
            <div class="mm-sep-line"></div>
        </div>

        <!-- Links nav -->
        <nav class="mm-nav" aria-label="Menú móvil">

            <a href="index.php" class="mm-link" aria-label="Ir al inicio">
                <i class="bi bi-house-fill" aria-hidden="true"></i>
                Inicio
            </a>

            <!-- Carrito con badge -->
            <a href="cart.php" class="mm-link mm-link-cart" aria-label="Ver carrito de compras">
                <i class="bi bi-cart3" aria-hidden="true"></i>
                <span>Carrito</span>
                <div class="mm-cart-right">
                    <?php if ($_nav_cart_count > 0): ?>
                    <span class="mm-cart-badge"><?= $_nav_cart_count ?> items</span>
                    <?php endif; ?>
                    <i class="bi bi-chevron-right mm-cart-chevron" aria-hidden="true"></i>
                </div>
            </a>

            <a href="index.php#contactanos" class="mm-link" aria-label="Ir a contacto">
                <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
                Contáctanos
            </a>

        </nav>

        <!-- Footer: redes + WhatsApp -->
        <div class="mm-footer">

            <div class="mm-footer-title">Síguenos</div>

            <div class="mm-social">
                <a href="https://www.facebook.com/profile.php?id=61553909536855"
                   target="_blank" rel="noopener noreferrer" aria-label="Facebook VP Motos">
                    <i class="bi bi-facebook" aria-hidden="true"></i>
                </a>
                <a href="https://www.tiktok.com/@vpmotos"
                   target="_blank" rel="noopener noreferrer" aria-label="TikTok VP Motos">
                    <i class="bi bi-tiktok" aria-hidden="true"></i>
                </a>
                <a href="https://instagram.com/vpmotos"
                   target="_blank" rel="noopener noreferrer" aria-label="Instagram VP Motos">
                    <i class="bi bi-instagram" aria-hidden="true"></i>
                </a>
            </div>

            <!-- WhatsApp -->
            <a href="https://wa.me/593996628440?text=Hola%20VP%20Motos%2C%20me%20gustar%C3%ADa%20consultar%20sobre%20sus%20productos."
               class="mm-whatsapp"
               target="_blank" rel="noopener noreferrer"
               aria-label="Escribir a VP Motos por WhatsApp">
                <i class="bi bi-whatsapp" aria-hidden="true"></i>
                <div class="mm-whatsapp-text">
                    <span class="mm-whatsapp-num">0996 628 440</span>
                    <span class="mm-whatsapp-sub">Escribir por WhatsApp</span>
                </div>
                <i class="bi bi-box-arrow-up-right" style="margin-left:auto; font-size:.75rem; opacity:.5;" aria-hidden="true"></i>
            </a>

        </div>

    </div><!-- /mm-content -->
</div><!-- /mobile-menu -->

<script>
(function () {
    const navbar     = document.getElementById('navbar');
    const hamburger  = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
    const backdrop   = document.getElementById('mm-backdrop');
    const closeBtn   = document.getElementById('mm-close');

    /* ── Abrir / cerrar ── */
    function openMenu() {
        mobileMenu.classList.add('active');
        hamburger.classList.add('open');
        hamburger.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        mobileMenu.classList.remove('active');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    if (hamburger)  hamburger.addEventListener('click', openMenu);
    if (closeBtn)   closeBtn.addEventListener('click', closeMenu);
    if (backdrop)   backdrop.addEventListener('click', closeMenu);

    /* Cerrar al hacer clic en un link dentro del menú */
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    /* Cerrar con Escape */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeMenu();
    });

    /* ── Scroll → navbar scrolled ── */
    window.addEventListener('scroll', () => {
        if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 50);
    }, { passive: true });

    /* ── Actualizar badge carrito (llamable globalmente) ── */
    window.actualizarBadgeNav = function (n) {
        const badge = document.getElementById('nav-cart-badge');
        if (!badge) return;
        badge.textContent = n;
        badge.style.display = n > 0 ? 'flex' : 'none';
    };
})();
</script>