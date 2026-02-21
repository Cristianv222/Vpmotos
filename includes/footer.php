<!-- Bootstrap Icons (si no está ya en el head) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap');

/* ══════════════════════════════════════════════════════
   BOTONES FLOTANTES — WhatsApp + Tienda
   Se mantienen fijos al hacer scroll
══════════════════════════════════════════════════════ */
.fab-wrap {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 800;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

/* Botón base */
.fab {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    border-radius: 50px;
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    letter-spacing: 0.1em;
    white-space: nowrap;
    cursor: pointer;
    border: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    overflow: hidden;
    position: relative;
}

/* Estado colapsado — solo ícono */
.fab-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.25rem;
    transition: all 0.3s;
    position: relative;
    z-index: 1;
}

.fab-label {
    font-size: 0.65rem;
    max-width: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-width 0.35s cubic-bezier(0.16,1,0.3,1),
                opacity 0.25s ease,
                padding 0.3s;
    white-space: nowrap;
    position: relative;
    z-index: 1;
}

/* Expande al hover */
.fab:hover .fab-label {
    max-width: 160px;
    opacity: 1;
    padding-right: 18px;
}

/* ── WhatsApp ── */
.fab-wa {
    background: #25D366;
    box-shadow: 0 6px 24px rgba(37,211,102,0.35);
    color: #fff;
    padding: 0;
    border-radius: 50px;
}

.fab-wa .fab-icon { background: transparent; }

.fab-wa:hover {
    box-shadow: 0 10px 36px rgba(37,211,102,0.5);
    transform: translateY(-3px);
}

.fab-wa .fab-label { color: #fff; }

/* ── Tienda ── */
.fab-shop {
    background: #D2ED05;
    box-shadow: 0 6px 24px rgba(210,237,5,0.35);
    color: #000;
    padding: 0;
    border-radius: 50px;
}

.fab-shop .fab-icon {
    background: rgba(0,0,0,0.1);
    border-radius: 50%;
    color: #000;
}

.fab-shop:hover {
    box-shadow: 0 10px 36px rgba(210,237,5,0.5);
    transform: translateY(-3px);
    background: #b8cf04;
}

.fab-shop .fab-label { color: #000; }

/* Pulso de atención (aparece de vez en cuando) */
.fab-wa::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50px;
    background: rgba(37,211,102,0.4);
    animation: fabPulse 3.5s ease-out infinite;
    z-index: 0;
}

@keyframes fabPulse {
    0%   { transform: scale(1); opacity: .5; }
    60%  { transform: scale(1.55); opacity: 0; }
    100% { transform: scale(1.55); opacity: 0; }
}

@media (max-width: 540px) {
    .fab-wrap { bottom: 20px; right: 16px; }
    .fab-icon { width: 46px; height: 46px; font-size: 1.1rem; }
}

/* ══════════════════════════════════════════════════════
   FOOTER
══════════════════════════════════════════════════════ */
.footer {
    background: #111;
    color: #fff;
    padding: 56px 60px 0;
    font-family: 'Audiowide', sans-serif;
    border-top: 1px solid rgba(255,255,255,0.06);
    position: relative;
}

/* Línea decorativa top */
.footer::before {
    content: '';
    position: absolute;
    top: 0; left: 50%; transform: translateX(-50%);
    width: 60px; height: 2px;
    background: #D2ED05;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr 1fr 1fr;
    gap: 48px;
    padding-bottom: 48px;
}

/* ── Columna logo ── */
.footer-brand {}

.footer-logo {
    width: auto;
    height: 80px;
    object-fit: contain;
    margin-bottom: 20px;
    display: block;
}

.footer-tagline {
    font-size: 0.6rem;
    letter-spacing: 0.18em;
    color: rgba(255,255,255,0.3);
    text-transform: uppercase;
    margin-bottom: 24px;
    display: block;
}

/* Info de contacto */
.footer-contact {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.fc-item {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: rgba(255,255,255,0.5);
    font-size: 0.68rem;
    letter-spacing: 0.05em;
    transition: color .2s;
}

.fc-item:hover { color: #D2ED05; }

.fc-item i {
    width: 30px; height: 30px;
    background: rgba(210,237,5,0.07);
    border: 1px solid rgba(210,237,5,0.12);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem;
    color: rgba(210,237,5,0.6);
    flex-shrink: 0;
    transition: all .2s;
}

.fc-item:hover i {
    background: rgba(210,237,5,0.14);
    border-color: rgba(210,237,5,0.35);
    color: #D2ED05;
}

/* ── Columnas nav ── */
.footer-col-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.58rem;
    letter-spacing: 0.28em;
    color: #D2ED05;
    text-transform: uppercase;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-col-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(210,237,5,0.15);
}

.footer-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
    list-style: none;
}

.footer-links a {
    color: rgba(255,255,255,0.42);
    text-decoration: none;
    font-size: 0.68rem;
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all .2s;
}

.footer-links a i { font-size: 0.7rem; color: rgba(210,237,5,0.4); transition: color .2s; }
.footer-links a:hover { color: #fff; padding-left: 4px; }
.footer-links a:hover i { color: #D2ED05; }

/* ── Columna redes ── */
.footer-social-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.footer-social-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 16px 10px;
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 10px;
    text-decoration: none;
    color: rgba(255,255,255,0.4);
    font-size: 0.52rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    transition: all .25s;
}

.footer-social-link i { font-size: 1.3rem; }

.footer-social-link:hover {
    border-color: rgba(210,237,5,0.3);
    color: #D2ED05;
    background: rgba(210,237,5,0.05);
    transform: translateY(-3px);
}

/* Whatsapp especial */
.fsl-wa {
    grid-column: 1 / -1;
    flex-direction: row;
    padding: 12px 16px;
    background: rgba(37,211,102,0.07);
    border-color: rgba(37,211,102,0.2);
    color: rgba(37,211,102,0.7);
    font-size: 0.62rem;
    letter-spacing: 0.06em;
    justify-content: flex-start;
    gap: 10px;
}

.fsl-wa i { font-size: 1.1rem; }

.fsl-wa .wa-num {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.62rem;
    color: #fff;
    display: block;
}

.fsl-wa .wa-sub {
    font-size: 0.52rem;
    color: rgba(37,211,102,0.6);
    display: block;
    letter-spacing: 0.08em;
}

.fsl-wa:hover {
    border-color: rgba(37,211,102,0.5);
    background: rgba(37,211,102,0.12);
    color: #25D366;
    transform: translateY(-2px);
}

/* ── Newsletter ── */
.newsletter-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.newsletter-form input {
    padding: 10px 14px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    color: #fff;
    font-family: 'Audiowide', sans-serif;
    font-size: 0.65rem;
    border-radius: 6px;
    outline: none;
    transition: border-color .2s;
}

.newsletter-form input:focus { border-color: rgba(210,237,5,0.4); }
.newsletter-form input::placeholder { color: rgba(255,255,255,0.2); }

.newsletter-form button {
    padding: 10px 16px;
    background: #D2ED05;
    border: none;
    color: #000;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    cursor: pointer;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all .25s;
}

.newsletter-form button:hover {
    background: #a8bc04;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(210,237,5,0.25);
}

.newsletter-form button i { font-size: 0.8rem; }

.newsletter-note {
    font-size: 0.55rem;
    color: rgba(255,255,255,0.18);
    letter-spacing: 0.06em;
    line-height: 1.6;
    display: flex;
    align-items: flex-start;
    gap: 6px;
}

.newsletter-note i { color: rgba(210,237,5,0.3); font-size: 0.65rem; flex-shrink: 0; margin-top: 1px; }

/* ── Footer bottom ── */
.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.06);
    padding: 18px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.footer-copy {
    font-size: 0.6rem;
    color: rgba(255,255,255,0.22);
    letter-spacing: 0.08em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-copy i { color: rgba(210,237,5,0.3); }

.footer-bottom-links {
    display: flex;
    gap: 20px;
}

.footer-bottom-links a {
    color: rgba(255,255,255,0.22);
    text-decoration: none;
    font-size: 0.58rem;
    letter-spacing: 0.1em;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: color .2s;
}

.footer-bottom-links a:hover { color: #D2ED05; }
.footer-bottom-links a i { font-size: 0.7rem; }

/* ══ RESPONSIVE ══ */
@media (max-width: 1024px) {
    .footer { padding: 48px 36px 0; }
    .footer-grid { grid-template-columns: 1fr 1fr; gap: 36px; }
}

@media (max-width: 640px) {
    .footer { padding: 40px 20px 0; }
    .footer-grid { grid-template-columns: 1fr; gap: 32px; }
    .footer-bottom { flex-direction: column; align-items: flex-start; gap: 10px; }
    .footer-social-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<!-- ══════════════════════════════════════════════════
     BOTONES FLOTANTES (fijos al scroll)
══════════════════════════════════════════════════ -->
<div class="fab-wrap" role="complementary" aria-label="Acciones rápidas">

    <!-- Tienda Online -->
    <a href="tienda.php" class="fab fab-shop" aria-label="Ir a la tienda online VP Motos">
        <div class="fab-icon">
            <i class="bi bi-bag-check-fill" aria-hidden="true"></i>
        </div>
        <span class="fab-label">Tienda Online</span>
    </a>

    <!-- WhatsApp -->
    <a href="https://wa.me/593996628440?text=Hola%20VP%20Motos%2C%20me%20gustar%C3%ADa%20consultar%20sobre%20sus%20productos."
       class="fab fab-wa"
       target="_blank" rel="noopener noreferrer"
       aria-label="Contactar VP Motos por WhatsApp">
        <div class="fab-icon">
            <i class="bi bi-whatsapp" aria-hidden="true"></i>
        </div>
        <span class="fab-label">WhatsApp</span>
    </a>

</div>

<!-- ══════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════ -->
<footer class="footer" role="contentinfo">

    <div class="footer-grid">

        <!-- ── Columna 1: Marca + Contacto ── -->
        <div class="footer-brand">
            <img src="./images/logo_vp.png"
                 alt="VP Motos — Repuestos y Accesorios para Motos en Ecuador"
                 class="footer-logo">
            <span class="footer-tagline">Potencia sobre dos ruedas</span>

            <div class="footer-contact">
                <a href="https://wa.me/593996628440" class="fc-item"
                   target="_blank" rel="noopener noreferrer" aria-label="WhatsApp VP Motos">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                    0996 628 440
                </a>
                <a href="tel:+593996628440" class="fc-item" aria-label="Llamar a VP Motos">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                    0996 628 440
                </a>
                <a href="mailto:info@vpmotos.ec" class="fc-item" aria-label="Email VP Motos">
                    <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                    info@vpmotos.ec
                </a>
                <a href="https://maps.google.com/?q=VP+MOTOS+Quito+Ecuador"
                   target="_blank" rel="noopener noreferrer"
                   class="fc-item" aria-label="Ubicación VP Motos">
                    <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                    Quito, Ecuador
                </a>
            </div>
        </div>

        <!-- ── Columna 2: Navegación ── -->
        <div>
            <div class="footer-col-title">
                <i class="bi bi-grid-3x3-gap-fill" aria-hidden="true"></i>
                Navegación
            </div>
            <ul class="footer-links" role="list">
                <li><a href="index.php"><i class="bi bi-house-fill" aria-hidden="true"></i>Inicio</a></li>
                <li><a href="tienda.php"><i class="bi bi-bag-fill" aria-hidden="true"></i>Tienda Online</a></li>
                <li><a href="cart.php"><i class="bi bi-cart3" aria-hidden="true"></i>Carrito</a></li>
                <li><a href="index.php#quienes-somos"><i class="bi bi-wrench-adjustable" aria-hidden="true"></i>Servicios</a></li>
                <li><a href="index.php#contactanos"><i class="bi bi-chat-dots-fill" aria-hidden="true"></i>Contáctanos</a></li>
            </ul>
        </div>

        <!-- ── Columna 3: Redes Sociales ── -->
        <div>
            <div class="footer-col-title">
                <i class="bi bi-share-fill" aria-hidden="true"></i>
                Redes
            </div>
            <div class="footer-social-grid">
                <a href="https://www.facebook.com/profile.php?id=61553909536855"
                   target="_blank" rel="noopener noreferrer"
                   class="footer-social-link" aria-label="VP Motos en Facebook">
                    <i class="bi bi-facebook" aria-hidden="true"></i>
                    Facebook
                </a>
                <a href="https://www.tiktok.com/@vpmotos"
                   target="_blank" rel="noopener noreferrer"
                   class="footer-social-link" aria-label="VP Motos en TikTok">
                    <i class="bi bi-tiktok" aria-hidden="true"></i>
                    TikTok
                </a>
                <a href="https://instagram.com/vpmotos"
                   target="_blank" rel="noopener noreferrer"
                   class="footer-social-link" aria-label="VP Motos en Instagram">
                    <i class="bi bi-instagram" aria-hidden="true"></i>
                    Instagram
                </a>
                <a href="https://maps.google.com/?q=VP+MOTOS+Quito+Ecuador"
                   target="_blank" rel="noopener noreferrer"
                   class="footer-social-link" aria-label="VP Motos en Google Maps">
                    <i class="bi bi-google" aria-hidden="true"></i>
                    Maps
                </a>
                <!-- WhatsApp destacado ancho completo -->
                <a href="https://wa.me/593996628440?text=Hola%20VP%20Motos%2C%20quiero%20consultar%20sobre%20sus%20productos."
                   target="_blank" rel="noopener noreferrer"
                   class="footer-social-link fsl-wa" aria-label="WhatsApp VP Motos">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                    <div>
                        <span class="wa-num">0996 628 440</span>
                        <span class="wa-sub">Escribir por WhatsApp</span>
                    </div>
                    <i class="bi bi-box-arrow-up-right" style="margin-left:auto; font-size:.7rem; opacity:.4;" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <!-- ── Columna 4: Newsletter ── -->
        <div>
            <div class="footer-col-title">
                <i class="bi bi-bell-fill" aria-hidden="true"></i>
                Novedades
            </div>
            <div class="newsletter-form">
                <input type="email" placeholder="Tu correo electrónico" aria-label="Correo electrónico para newsletter">
                <button type="button" aria-label="Suscribirse al newsletter">
                    <i class="bi bi-send-fill" aria-hidden="true"></i>
                    Suscribirse
                </button>
                <p class="newsletter-note">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                    Sin spam. Solo ofertas y novedades de VP Motos.
                </p>
            </div>
        </div>

    </div>

    <!-- Footer bottom -->
    <div class="footer-bottom">
        <div class="footer-copy">
            <i class="bi bi-c-circle" aria-hidden="true"></i>
            <?= date('Y') ?> VP Motos — Todos los derechos reservados
        </div>
        <div class="footer-bottom-links">
            <a href="tienda.php" aria-label="Ir a la tienda">
                <i class="bi bi-bag-check" aria-hidden="true"></i>Tienda
            </a>
            <a href="cart.php" aria-label="Ir al carrito">
                <i class="bi bi-cart3" aria-hidden="true"></i>Carrito
            </a>
            <a href="index.php#contactanos" aria-label="Ir a contacto">
                <i class="bi bi-chat-dots" aria-hidden="true"></i>Contacto
            </a>
        </div>
    </div>

</footer>