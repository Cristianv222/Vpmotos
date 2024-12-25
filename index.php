<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/index.css">
  <title>Vpmotos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>
<body>
<?php include './includes/menu.php'; ?>
  <header>
  <div class="carousel-container">
        <div class="carousel">
            <!-- Slides se añadirán dinámicamente con JavaScript -->
        </div>
        <div class="carousel-controls">
            <button class="control-btn prev-btn">&lt;</button>
            <button class="control-btn next-btn">&gt;</button>
        </div>
        <div class="carousel-indicators">
            <!-- Indicadores se añadirán dinámicamente con JavaScript -->
        </div>
  </header>
  <div class="carousel-container">
    <div class="carousel-item">
      <img src="/api/placeholder/400/300" alt="Placeholder Image 1">
      <button class="open-modal" data-modal-id="modal1">Learn More</button>
      <div class="modal" id="modal1">
        <div class="modal-content">
          <span class="close-modal">&times;</span>
          <h2>Game 1</h2>
          <p>This is a description of the first game in the carousel.</p>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <img src="/api/placeholder/400/300" alt="Placeholder Image 2">
      <button class="open-modal" data-modal-id="modal2">Learn More</button>
      <div class="modal" id="modal2">
        <div class="modal-content">
          <span class="close-modal">&times;</span>
          <h2>Game 2</h2>
          <p>This is a description of the second game in the carousel.</p>
        </div>
      </div>
    </div>
    <!-- Add more carousel items as needed -->
  </div>
  <script src="./js/scripts.js"></script>
</body>
</html>