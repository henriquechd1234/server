const topTenGrid = document.querySelector('.top-ten-grid');
const prevButton = document.querySelector('.prev-button');
const nextButton = document.querySelector('.next-button');
const topTengrid = document.querySelector ('top-ten-grid');

prevButton.addEventListener('click', () => {
    topTenGrid.scrollBy({
        left: -400, 
        behavior: 'smooth'
    });
});

nextButton.addEventListener('click', () => {
    topTenGrid.scrollBy({
        left: 400, 
        behavior: 'smooth'
    });
});

topTenGrid.addEventListener('wheel', (event) => {
    event.preventDefault();
    topTenGrid.scrollBy({
        left: event.deltaY < 0 ? -400 : 400, 
        behavior: 'smooth'
    });
});

let currentSlide = 0;

function moveSlide(direction) {
    const slides = document.querySelector('.slider');
    const totalSlides = slides.children.length;

    currentSlide += direction;

    if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    } else if (currentSlide >= totalSlides) {
        currentSlide = 0;
    }

    const offset = -currentSlide * 100;
    slides.style.transform = `translateX(${offset}%)`;
}

setInterval(() => {
    moveSlide(1);
}, 5000);

const prevButton = document.querySelector('.carousel-prev');
const nextButton = document.querySelector('.carousel-next');
const carouselItems = document.querySelector('.carousel-items');

let scrollAmount = 0;

prevButton.addEventListener('click', () => {
  carouselItems.scrollBy({
    top: 0,
    left: -300, // valor do deslocamento ao clicar
    behavior: 'smooth'
  });
});

nextButton.addEventListener('click', () => {
  carouselItems.scrollBy({
    top: 0,
    left: 300, // valor do deslocamento ao clicar
    behavior: 'smooth'
  });
});
