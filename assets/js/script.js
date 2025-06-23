/* Carrusel 2 */
  const swiper = new Swiper('.mySwiper', {
    slidesPerView: 4,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev'
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    },
    breakpoints: {
      992: { slidesPerView: 4 },
      768: { slidesPerView: 2 },
      576: { slidesPerView: 1 }
    }
  });