let slideIndex = 0; 
let isVideoPlaying = false;

// Inicializar el video cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
  const video = document.getElementById('video-slide');
  const volumeControl = document.querySelector('.volume-control');
  
  if (video) {
    // Asegurarnos de que el video se reproduzca automáticamente
    video.play().catch(function(error) {
      console.log("Error al reproducir el video:", error);
    });

    // Manejar el control de volumen
    volumeControl.addEventListener('input', function(e) {
      video.volume = e.target.value;
      if (video.volume > 0) {
        video.muted = false;
      } else {
        video.muted = true;
      }
    });

    // Prevenir que el usuario pause el video
    video.addEventListener('pause', function() {
      if (!video.ended) {
        video.play();
      }
    });
  }
});

function moveSlide(step) { 
  const slides = document.querySelectorAll('.slide'); 
  const video = document.getElementById('video-slide'); 
 
  slideIndex += step; 
 
  if (slideIndex < 0) { 
    slideIndex = slides.length - 1;
  } else if (slideIndex >= slides.length) { 
    slideIndex = 0;
  } 
 
  document.querySelector('.carrusel').style.transform = `translateX(${-slideIndex * 100}%)`; 
} 

// Iniciar el carrusel
let intervalId = setInterval(() => { 
  moveSlide(1); 
}, 80000);

//seccion carrusel infinito
document.addEventListener('DOMContentLoaded', () => {
  const infiniteCarousel = document.querySelector('.infinite-carousel');
  let dragStart = null;
  let currentRotation = 0;

  const handleStart = (e) => {
    dragStart = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
    infiniteCarousel.style.animation = 'none';
  };

  const handleMove = (e) => {
    if (dragStart === null) return;
    
    const currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
    const delta = currentX - dragStart;
    const rotation = (delta / 5) + currentRotation;
    infiniteCarousel.style.transform = `rotateY(${rotation}deg)`;
  };

  const handleEnd = (e) => {
    if (dragStart === null) return;
    
    const currentX = e.type === 'mouseup' ? e.clientX : 
                    e.type === 'touchend' ? e.changedTouches[0].clientX : 
                    dragStart;
    currentRotation = (currentX - dragStart) / 5 + currentRotation;
    dragStart = null;
    infiniteCarousel.style.animation = 'infinite-spin 30s infinite linear';
  };

  infiniteCarousel.addEventListener('mousedown', handleStart);
  document.addEventListener('mousemove', handleMove);
  document.addEventListener('mouseup', handleEnd);

  infiniteCarousel.addEventListener('touchstart', handleStart);
  document.addEventListener('touchmove', handleMove);
  document.addEventListener('touchend', handleEnd);
});