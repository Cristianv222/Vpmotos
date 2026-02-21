// Main scripts file

document.addEventListener('DOMContentLoaded', function () {
  // Ensure video plays (fallback for some browsers)
  const video = document.querySelector('.hero-video');
  if (video) {
    video.play().catch(e => {
      console.log("Autoplay prevented:", e);
      // Usually muted autoplay works, but if not, we can show a play button overlay
    });
  }
});