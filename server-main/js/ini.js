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
