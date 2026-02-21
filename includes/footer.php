<style>
@import url('https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&display=swap');

.footer {
    background: #141414;
    color: white;
    padding: 20px 40px;
    font-family: Audiowide, sans-serif;
    margin-top: 60px;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 30px;
}

.footer-left {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.footer-logo {
    width: 150px;
    height: 100px;
    object-fit: contain;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
    font-size: 14px;
    margin-top: 20px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.footer-nav {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.nav-column {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.nav-column h4 {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.65rem;
    letter-spacing: 0.2em;
    color: #D2ED05;
    margin-bottom: 4px;
}

.nav-column a {
    color: #aaa;
    text-decoration: none;
    font-size: 14px;
    transition: color .2s;
}

.nav-column a:hover { color: #D2ED05; }

.footer-social-icons {
    display: flex;
    gap: 20px;
    margin-top: 4px;
}

.footer-social-icons img {
    width: 30px;
    height: 30px;
    opacity: .7;
    transition: opacity .2s, transform .2s;
}

.footer-social-icons img:hover { opacity: 1; transform: scale(1.1); }

.newsletter {
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-width: 200px;
}

.newsletter h4 {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.65rem;
    letter-spacing: 0.2em;
    color: #D2ED05;
}

.newsletter input {
    padding: 8px 12px;
    width: 200px;
    background: transparent;
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    font-family: Audiowide, sans-serif;
    font-size: 0.7rem;
    border-radius: 4px;
    outline: none;
    transition: border-color .2s;
}

.newsletter input:focus { border-color: rgba(210,237,5,0.5); }
.newsletter input::placeholder { color: #555; }

.newsletter button {
    padding: 8px 20px;
    background: #D2ED05;
    border: none;
    color: #000;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    cursor: pointer;
    border-radius: 4px;
    transition: background .2s;
}

.newsletter button:hover { background: #a8bc04; }

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0 0;
    margin-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.07);
    font-size: 0.62rem;
    color: #555;
    flex-wrap: wrap;
    gap: 10px;
}

.footer-bottom-links { display: flex; gap: 20px; }
.footer-bottom-links a { color: #555; text-decoration: none; transition: color .2s; }
.footer-bottom-links a:hover { color: #D2ED05; }

@media(max-width: 768px) {
    .footer { padding: 20px 20px; }
    .footer-content { flex-direction: column; gap: 24px; }
    .newsletter input { width: 100%; }
}
</style>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-left">
            <img src="./images/logo_vp.png" alt="Logo VP Motos" class="footer-logo">
            <div class="contact-info">
                <div class="contact-item">
                    <span>📱</span><span>0996628440</span>
                </div>
                <div class="contact-item">
                    <span>✉️</span><span>info@vp-motos.com</span>
                </div>
                <div class="contact-item">
                    <span>📍</span><span>Tulcán, Ecuador</span>
                </div>
            </div>
        </div>

        <nav class="footer-nav">
            <div class="nav-column">
                <h4>ENLACES</h4>
                <a href="index.php">Inicio</a>
                <a href="tienda.php">Tienda</a>
                <a href="cart.php">Carrito</a>
                <a href="index.php#quienes-somos">Quiénes Somos</a>
                <a href="index.php#contactanos">Contáctanos</a>
                <a href="index.php#blog">Blog</a>
            </div>
            <div class="nav-column">
                <h4>REDES SOCIALES</h4>
                <div class="footer-social-icons">
                    <a href="https://www.facebook.com/profile.php?id=61553909536855&mibextid=ZbWKwL" target="_blank">
                        <img src="./images/facebook.png" alt="Facebook">
                    </a>
                    <a href="https://www.tiktok.com/@vpmotos" target="_blank">
                        <img src="./images/tik-tok.png" alt="TikTok">
                    </a>
                    <a href="https://instagram.com" target="_blank">
                        <img src="./images/instagram.png" alt="Instagram">
                    </a>
                </div>
            </div>
        </nav>

        <div class="newsletter">
            <h4>WEEKLY NEWSLETTER</h4>
            <input type="email" placeholder="Email">
            <button type="button">Subscribe</button>
        </div>
    </div>

    <div class="footer-bottom">
        <span>© <?= date('Y') ?> VP Motos — Todos los derechos reservados</span>
        <div class="footer-bottom-links">
            <a href="tienda.php">Tienda</a>
            <a href="cart.php">🛒 Carrito</a>
        </div>
    </div>
</footer>