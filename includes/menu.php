<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Navegación Transparente</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #007bff;
            --text-color-light: #fff;
            --text-color-dark: #333;
            --overlay-color: rgba(0, 0, 0, 0.5);
        }

        body, html {
            font-family: Arial, sans-serif;
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
            padding: 5px 80px;
            background-color: transparent;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background-color: var(--overlay-color);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 110px;
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
            background-color: #ccc;
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
                <a href="#" class="cta-button">Botón de Acción</a>
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
