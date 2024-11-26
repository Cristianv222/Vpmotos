class Carousel {
    constructor(container, slides) {
        this.container = document.querySelector(container);
        this.carouselElement = this.container.querySelector('.carousel');
        this.indicatorsContainer = this.container.querySelector('.carousel-indicators');
        this.prevBtn = this.container.querySelector('.prev-btn');
        this.nextBtn = this.container.querySelector('.next-btn');
        this.slides = slides;
        this.currentIndex = 0;
        this.autoSlideInterval = null;

        this.initCarousel();
        this.setupEventListeners();
        this.startAutoSlide();
    }

    initCarousel() {
        this.slides.forEach((slide, index) => {
            // Crear slide
            const slideElement = document.createElement('div');
            slideElement.classList.add('slide');
            slideElement.innerHTML = `
                <img src="${slide.image}" alt="${slide.title}">
                <div class="slide-content">
                    <div class="slide-text">
                        <h2>${slide.title}</h2>
                        <p>${slide.description}</p>
                        <a href="${slide.link}" class="action-btn">${slide.buttonText}</a>
                    </div>
                </div>
            `;
            this.carouselElement.appendChild(slideElement);

            // Crear indicador
            const indicator = document.createElement('div');
            indicator.classList.add('indicator');
            if (index === 0) indicator.classList.add('active');
            indicator.addEventListener('click', () => this.goToSlide(index));
            this.indicatorsContainer.appendChild(indicator);
        });
    }

    setupEventListeners() {
        this.prevBtn.addEventListener('click', () => this.prevSlide());
        this.nextBtn.addEventListener('click', () => this.nextSlide());
    }

    nextSlide() {
        this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        this.updateCarousel();
    }

    prevSlide() {
        this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
        this.updateCarousel();
    }

    goToSlide(index) {
        this.currentIndex = index;
        this.updateCarousel();
    }

    updateCarousel() {
        const offset = -this.currentIndex * 100;
        this.carouselElement.style.transform = `translateX(${offset}%)`;

        // Actualizar indicadores
        const indicators = this.indicatorsContainer.querySelectorAll('.indicator');
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === this.currentIndex);
        });
    }

    startAutoSlide() {
        this.autoSlideInterval = setInterval(() => {
            this.nextSlide();
        }, 8000);
    }

    stopAutoSlide() {
        clearInterval(this.autoSlideInterval);
    }
}

// Ejemplo de uso
const slidesData = [
    {
        image: '/api/placeholder/1200/600',
        title: 'Título de Slide 1',
        description: 'Descripción detallada del primer slide con información interesante.',
        link: '#slide1',
        buttonText: 'Más Información'
    },
    {
        image: '/api/placeholder/1200/600',
        title: 'Título de Slide 2',
        description: 'Descripción detallada del segundo slide con información relevante.',
        link: '#slide2',
        buttonText: 'Explorar'
    },
    {
        image: '/api/placeholder/1200/600',
        title: 'Título de Slide 3',
        description: 'Descripción detallada del tercer slide con información adicional.',
        link: '#slide3',
        buttonText: 'Descubrir'
    }
];

new Carousel('.carousel-container', slidesData);