<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$_nav_cart_count = array_sum(array_column($_SESSION['carrito'] ?? [], 'cantidad'));
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap');

*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

:root {
    --primary-color: #D2ED05;
    --text-color-light: #fff;
    --text-color-dark: #D2ED05;
    --overlay-color: rgba(34,34,34,0.85);
}

.navbar {
    position: fixed; top: 0; left: 0; width: 100%;
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 80px;
    background-color: transparent;
    z-index: 900; transition: all 0.3s ease;
}
.navbar.scrolled {
    background-color: var(--overlay-color);
    backdrop-filter: blur(20px);
    box-shadow: 0 2px 10px rgba(69,70,69,0.4);
    padding: 10px 80px;
}
.navbar.scrolled .logo img { height: 80px; }
.navbar.scrolled .nav-links a { font-size: 1rem; }
.navbar.scrolled .cta-button { padding: 8px 18px; }

.logo { display: flex; align-items: center; }
.logo img { height: 150px; cursor: pointer; transition: height .3s; }

.nav-links { display: flex; align-items: center; gap: 30px; }
.nav-links a {
    text-decoration: none; color: var(--text-color-light);
    font-family: Audiowide, sans-serif; font-weight: 500; transition: color 0.3s ease;
}
.navbar.scrolled .nav-links a { color: var(--text-color-dark); }
.nav-links a:hover { color: var(--primary-color); }

.nav-cta { display: flex; align-items: center; gap: 12px; }

.vertical-line {
    height: 30px; width: 1px;
    background-color: rgba(66,218,6,0.966);
    margin: 0 15px; transition: background-color 0.3s ease;
}
.navbar.scrolled .vertical-line { background-color: #96e948; }

.cta-button {
    padding: 10px 20px; background-color: transparent;
    color: var(--text-color-light); border: 2px solid var(--text-color-light);
    border-radius: 5px; text-decoration: none; transition: all 0.3s ease;
    font-family: Audiowide, sans-serif; font-size: 0.8rem;
}
.navbar.scrolled .cta-button {
    background-color: var(--primary-color); color: #000; border-color: var(--primary-color);
}

.social-icons { display: flex; gap: 15px; margin-left: 10px; }
.social-icon { width: 30px; height: 30px; transition: transform 0.3s ease; }
.social-icon:hover { transform: scale(1.1); }

/* CARRITO EN NAV */
.nav-cart {
    position: relative; display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 50%;
    background: rgba(210,237,5,0.1); border: 1px solid rgba(210,237,5,0.3);
    color: var(--primary-color); text-decoration: none;
    transition: all .2s; margin-left: 8px;
}
.nav-cart:hover { background: rgba(210,237,5,0.2); transform: translateY(-2px); }
.nav-cart-badge {
    position: absolute; top: -5px; right: -5px;
    background: var(--primary-color); color: #000;
    font-size: 0.55rem; font-family: 'Orbitron', sans-serif; font-weight: 700;
    width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    line-height: 1;
}

/* HAMBURGUESA */
.hamburger-menu {
    display: none; flex-direction: column; cursor: pointer; gap: 4px;
}
.hamburger-line {
    width: 25px; height: 3px; background-color: var(--text-color-light);
    margin: 0; transition: all 0.3s ease;
}
.navbar.scrolled .hamburger-line { background-color: var(--text-color-dark); }

/* MENÚ MÓVIL */
.mobile-menu {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    position: fixed; inset: 0;
    background-color: rgba(10,10,10,0.97);
    z-index: 1100; transform: translateX(-100%); transition: transform 0.3s ease;
}
.mobile-menu.active { transform: translateX(0); }
.mobile-menu-content { text-align: center; }
.mobile-menu-logo { position: absolute; top: 20px; left: 20px; }
.mobile-menu-logo img { height: 45px; }
.mobile-menu-close {
    position: absolute; top: 16px; right: 20px;
    font-size: 2rem; cursor: pointer; color: #fff; line-height: 1;
}
.mobile-menu-close:hover { color: var(--primary-color); }
.mobile-nav-links { display: flex; flex-direction: column; gap: 24px; margin-bottom: 30px; }
.mobile-nav-links a {
    text-decoration: none; color: #fff;
    font-family: 'Orbitron', sans-serif; font-size: 1.1rem; letter-spacing: 0.15em;
    transition: color .2s;
}
.mobile-nav-links a:hover { color: var(--primary-color); }

@media screen and (max-width: 900px) {
    .navbar { padding: 15px 20px; }
    .navbar.scrolled { padding: 10px 20px; }
    .nav-links, .nav-cta { display: none; }
    .hamburger-menu { display: flex; }
    .logo img { height: 80px; }
}
</style>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">
        <a href="index.php">
            <img src="./images/logo_vp.png" alt="Logo VP Motos">
        </a>
    </div>

    <div class="nav-links">
        <a href="index.php">Inicio</a>
        <a href="tienda.php">Tienda</a>
        <a href="index.php#quienes-somos">Quienes Somos</a>
        <a href="index.php#blog">Blog</a>
        <a href="index.php#contactanos">Contáctanos</a>
    </div>

    <div class="nav-cta">
        <div class="vertical-line"></div>
        <a href="#" class="cta-button">Registrate</a>
        <div class="social-icons">
            <a href="https://www.facebook.com/profile.php?id=61553909536855&mibextid=ZbWKwL" target="_blank">
                <img src="./images/logo_vp.png" alt="Facebook" class="social-icon">
            </a>
            <a href="https://www.tiktok.com/@vpmotos" target="_blank">
                <img src="./images/tik-tok.png" alt="TikTok" class="social-icon">
            </a>
            <a href="https://instagram.com" target="_blank">
                <img src="./images/instagram.png" alt="Instagram" class="social-icon">
            </a>
        </div>
        <!-- Botón carrito -->
        <a href="cart.php" class="nav-cart" id="nav-cart-btn" title="Carrito">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <span class="nav-cart-badge" id="nav-cart-badge"
                  style="<?= $_nav_cart_count > 0 ? '' : 'display:none' ?>">
                <?= $_nav_cart_count ?>
            </span>
        </a>
    </div>

    <!-- Hamburguesa -->
    <div class="hamburger-menu" id="hamburger-menu">
        <div class="hamburger-line"></div>
        <div class="hamburger-line"></div>
        <div class="hamburger-line"></div>
    </div>
</nav>

<!-- MENÚ MÓVIL -->
<div class="mobile-menu" id="mobile-menu">
    <div class="mobile-menu-logo">
        <a href="index.php">
            <img src="./images/logo_vp.png" alt="Logo VP Motos">
        </a>
    </div>
    <div class="mobile-menu-close" id="mobile-menu-close">&times;</div>
    <div class="mobile-menu-content">
        <div class="mobile-nav-links">
            <a href="index.php">Inicio</a>
            <a href="tienda.php">Tienda</a>
            <a href="cart.php">
                🛒 Carrito<?php if ($_nav_cart_count > 0): ?> (<?= $_nav_cart_count ?>)<?php endif; ?>
            </a>
            <a href="index.php#quienes-somos">Quienes Somos</a>
            <a href="index.php#blog">Blog</a>
            <a href="index.php#contactanos">Contáctanos</a>
        </div>
        <a href="#" class="cta-button" style="margin-top:20px;display:inline-block;">Registrate</a>
    </div>
</div>

<script>
(function(){
    const hamburger  = document.getElementById('hamburger-menu');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeBtn   = document.getElementById('mobile-menu-close');
    const navbar     = document.querySelector('.navbar');

    if (hamburger)  hamburger.addEventListener('click',  () => mobileMenu.classList.add('active'));
    if (closeBtn)   closeBtn.addEventListener('click',   () => mobileMenu.classList.remove('active'));

    window.addEventListener('scroll', () => {
        if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // Función global: actualiza badge del carrito desde cualquier página
    window.actualizarBadgeNav = function(n) {
        const badge = document.getElementById('nav-cart-badge');
        if (!badge) return;
        badge.textContent = n;
        badge.style.display = n > 0 ? 'flex' : 'none';
    };
})();
</script>