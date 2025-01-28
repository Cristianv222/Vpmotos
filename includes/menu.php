<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Navegación Transparente</title>
    <link rel="stylesheet" href="./menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
        <!-- Barra de Navegación -->
        <nav class="navbar">
            <div class="logo">
                <a href="index.html">
                    <img src="../images/logo_vp.png" alt="Logo de la Empresa">
                </a>
            </div>

            <div class="nav-links">
                <a href="#inicio">Inicio</a>
                <a href="#quienes-somos">Quienes Somos</a>
                <a href="#blog">Blog</a>
                <a href="#contactanos">Contáctanos</a>
            </div>

            <div class="nav-cta">
                <div class="vertical-line"></div>
                <a href="#" class="cta-button">Registrate</a>
                <div class="social-icons">
                    <a href="https://www.facebook.com/profile.php?id=61553909536855&mibextid=ZbWKwL" target="_blank">
                        <img src="../images/logo_vp.png" alt="Facebook" class="social-icon">
                    </a>
                    <a href="https://www.tiktok.com/@vpmotos?_r=1&_d=ed2li323fc07ci&sec_uid=MS4wLjABAAAAiiqGueh4oEdVqXKUJaQONT7wdC9QIM5rcbC9KIobReDvKYBDlo0jKlTjMA51SQZd&share_author_id=7311808382850663430&sharer_language=es&source=h5_m&u_code=edelaci27g8ie1&timestamp=1735231333&user_id=7355627490964096006&sec_user_id=MS4wLjABAAAAxmUN-mP54Fpvd6ObbqmUpl0b-YH5k6sBYs1L_nbuW2zfh-sJRtWmMKESJrylAdDx&utm_source=copy&utm_campaign=client_share&utm_medium=android&share_iid=7450448544441861894&share_link_id=0841f2cc-9c20-48df-961d-efb89b623c06&share_app_id=1233&ugbiz_name=ACCOUNT&social_share_type=5&enable_checksum=1" target="_blank">
                        <img src="../images/tik-tok.png" alt="Twitter" class="social-icon">
                    </a>
                    <a href="https://instagram.com" target="_blank">
                        <img src="../images/instagram.png" alt="Instagram" class="social-icon">
                    </a>
                </div>
            </div>

            <!-- Menú Hamburguesa -->
            <div class="hamburger-menu">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
        </nav>

    <!-- Menú Móvil -->
    <div class="mobile-menu">
        <div class="mobile-menu-logo">
            <a href="index.html">
                <img src="../images/logo_vp.png" alt="Logo de la Empresa">
            </a>
        </div>

        <div class="mobile-menu-close">&times;</div>

        <div class="mobile-menu-content">
            <div class="mobile-nav-links">
                <a href="#inicio">Inicio</a>
                <a href="#quienes-somos">Quienes Somos</a>
                <a href="#blog">Blog</a>
                <a href="#contactanos">Contáctanos</a>
            </div>

            <a href="#" class="cta-button">Botón de Acción</a>
        </div>
    </div>

    <script>
        // Espera a que el DOM cargue completamente
        document.addEventListener('DOMContentLoaded', () => {
            const hamburgerMenu = document.querySelector('.hamburger-menu');
            const mobileMenu = document.querySelector('.mobile-menu');
            const mobileMenuClose = document.querySelector('.mobile-menu-close');
            const navbar = document.querySelector('.navbar');

            // Abrir el menú móvil
            hamburgerMenu.addEventListener('click', () => {
                mobileMenu.classList.add('active');
            });

            // Cerrar el menú móvil
            mobileMenuClose.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
            });

            // Cambiar la barra de navegación al hacer scroll
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        });
    </script>
</body>
</html>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary-color: #D2ED05;
    --text-color-light: #fff;
    --text-color-dark: #D2ED05;
    --overlay-color: rgba(34, 34, 34, 0.596);
}

body, html {
    font-family: Audiowide, sans-serif;
    line-height: 1.6;
    scroll-behavior: smooth;
    background-color: #333;
}

.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 80px; /* Paddings grandes por defecto */
    background-color: transparent;
    z-index: 1000;
    transition: all 0.3s ease;
}

.navbar.scrolled {
    background-color: var(--overlay-color);
    backdrop-filter: blur(100px);
    box-shadow: 0 2px 10px rgba(69, 70, 69, 0.26);
    padding: 10px 80px; /* Reducción del padding al hacer scroll */
}

.navbar.scrolled .logo img {
    height: 80px; /* Reducir el tamaño del logo al hacer scroll */
}

.navbar.scrolled .nav-links a {
    font-size: 1rem; /* Reducir el tamaño de la fuente al hacer scroll */
}

.navbar.scrolled .cta-button {
    padding: 8px 18px; /* Reducir el padding del botón */
}

.logo {
    display: flex;
    align-items: center;
}

.logo img {
    height: 150px;
    cursor: pointer;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 30px;
}

.nav-links a {
    text-decoration: none;
    color: var(--text-color-light);
    font-weight: 500;
    transition: color 0.3s ease;
}

.navbar.scrolled .nav-links a {
    color: var(--text-color-dark);
}

.nav-links a:hover {
    color: var(--primary-color);
}

.nav-cta {
    display: flex;
    align-items: center;
}

.vertical-line {
    height: 30px;
    width: 1px;
    background-color: rgba(66, 218, 6, 0.966);
    margin: 0 15px;
    transition: background-color 0.3s ease;
}

.navbar.scrolled .vertical-line {
    background-color: #96e948;
}

.cta-button {
    padding: 10px 20px;
    background-color: transparent;
    color: var(--text-color-light);
    border: 2px solid var(--text-color-light);
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.navbar.scrolled .cta-button {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.social-icons {
    display: flex;
    gap: 15px;
    margin-left: 20px; /* Espacio entre el botón de registrarse y los iconos */
}

.social-icon {
    width: 30px; /* Tamaño de los iconos */
    height: 30px;
    transition: transform 0.3s ease;
}

.social-icon:hover {
    transform: scale(1.1); /* Efecto al pasar el mouse */
}

.hamburger-menu {
    display: none;
    flex-direction: column;
    cursor: pointer;
}

.hamburger-line {
    width: 25px;
    height: 3px;
    background-color: var(--text-color-light);
    margin: 3px 0;
    transition: all 0.3s ease;
}

.navbar.scrolled .hamburger-line {
    background-color: var(--text-color-dark);
}

.mobile-menu {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    z-index: 1100;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
}

.mobile-menu.active {
    transform: translateX(0);
}

.mobile-menu-content {
    text-align: center;
}

.mobile-menu-logo {
    position: absolute;
    top: 20px;
    left: 20px;
}

.mobile-menu-logo img {
    height: 40px;
}

.mobile-menu-close {
    position: absolute;
    top: 20px;
    right: 20px;
    font-size: 30px;
    cursor: pointer;
}

.mobile-nav-links {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.mobile-nav-links a {
    text-decoration: none;
    color: var(--text-color-dark);
    font-size: 1.2rem;
}

@media screen and (max-width: 768px) {
    .navbar {
        padding: 15px 20px;
    }

    .nav-links, .nav-cta {
        display: none;
    }

    .hamburger-menu {
        display: flex;
    }
}

</style>