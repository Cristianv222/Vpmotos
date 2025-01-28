<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<footer class="footer">
    <div class="footer-content">
      <div class="footer-left">
        <img src="../images/logo_vp.png" alt="Logo" class="footer-logo">
        <div class="contact-info">
          <div class="contact-item">
            <span>📱</span>
            <span>0996628440</span>
          </div>
          <div class="contact-item">
            <span>✉️</span>
            <span>info@vp-motos.com</span>
          </div>
          <div class="contact-item">
            <span>📍</span>
            <span>1234 Example Street</span>
          </div>
        </div>
      </div>
      
      <nav class="footer-nav">
        <div class="nav-column">
          <h4>ENLACES</h4>
          <a href="#">Inicio</a>
          <a href="#">Quienes Somos</a>
          <a href="#">Contactanos</a>
          <a href="#">Blog</a>
        </div>
        <div class="nav-column">
          <h4>REDES SOCIALES</h4>
          <div class="social-icons">
            <a href="https://www.facebook.com/profile.php?id=61553909536855&mibextid=ZbWKwL"><img src="../images/facebook.png" alt="Facebook"></a>
            <a href="https://www.tiktok.com/@vpmotos?_r=1&_d=ed2li323fc07ci&sec_uid=MS4wLjABAAAAiiqGueh4oEdVqXKUJaQONT7wdC9QIM5rcbC9KIobReDvKYBDlo0jKlTjMA51SQZd&share_author_id=7311808382850663430&sharer_language=es&source=h5_m&u_code=edelaci27g8ie1&timestamp=1735231333&user_id=7355627490964096006&sec_user_id=MS4wLjABAAAAxmUN-mP54Fpvd6ObbqmUpl0b-YH5k6sBYs1L_nbuW2zfh-sJRtWmMKESJrylAdDx&utm_source=copy&utm_campaign=client_share&utm_medium=android&share_iid=7450448544441861894&share_link_id=0841f2cc-9c20-48df-961d-efb89b623c06&share_app_id=1233&ugbiz_name=ACCOUNT&social_share_type=5&enable_checksum=1"><img src="../images/tik-tok.png" alt="Twitter"></a>
            <a href="#"><img src="../images/instagram.png" alt="Instagram"></a>
          </div>
        </div>
      </nav>
      
      <div class="newsletter">
        <h4>WEEKLY NEWSLETTER</h4>
        <input type="email" placeholder="Email">
        <button>Subscribe</button>
      </div>
    </div>
  </footer>

</body>
</html>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

.footer {
    background: #141414;
    color: white;
    padding: 20px 40px;
    font-family: Audiowide, sans-serif;
  }
  
  .footer-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }
  
  .footer-left {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  
  .footer-logo {
    width: 150px;
    height: 100px;
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
  }
  
  .nav-column {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  
  .nav-column a {
    color: white;
    text-decoration: none;
    font-size: 14px;
  }
  
  .social-icons {
    display: flex;
    gap: 20px;
  }
  
  .social-icons img {
    width: 30px;
    height: 30px;
  }
  
  .newsletter {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  
  .newsletter input {
    padding: 8px;
    width: 200px;
    background: transparent;
    border: 1px solid white;
    color: white;
  }
  
  .newsletter button {
    padding: 8px 20px;
    background: #ff69b4;
    border: none;
    color: white;
    cursor: pointer;
  }
</style>