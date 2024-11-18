const menuToggle = document.querySelector('.menu-toggle');
const mainMenu = document.querySelector('.main-menu');

menuToggle.addEventListener('click', () => {
  mainMenu.style.display = mainMenu.style.display === 'flex' ? 'none' : 'flex';
});