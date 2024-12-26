<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Navegación Transparente</title>
    <link rel="stylesheet" href="../includes/menu.css">
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
                        <img src="../images/facebook.png" alt="Facebook" class="social-icon">
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
