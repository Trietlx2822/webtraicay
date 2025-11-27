let login = document.querySelector('.login-form');
document.querySelector('#login-btn').onclick = () =>{
    login.classList.toggle('active');
    cart.classList.remove('active');
    nvabar.classList.remove('active');
}

//* review */
var swiper = new Swiper(".review-slider", {
    spaceBetween: 20,
    centeredSlides: true,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    loop: true,
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 2,
        },
        991: {
            slidesPerView: 3,
        },
    },
});