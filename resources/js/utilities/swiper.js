// core version + navigation, pagination modules:
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade, FreeMode } from 'swiper/modules';
// import Swiper and modules styles
import 'swiper/css';
// import 'swiper/css/navigation';
// import 'swiper/css/pagination';
// import 'swiper/css/effect-fade';
import 'swiper/css/free-mode';

// Initialize Swiper when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  // Find all swiper containers
  const swiperContainers = document.querySelectorAll('.swiper');

  swiperContainers.forEach((container) => {
    // Check if this swiper is already initialized
    if (container.swiper) {
      return;
    }

    // Check if this is a platform logos swiper
    const isPlatformLogos = container.classList.contains('platform-logos-swiper');

    // Initialize Swiper for this container
    const swiper = new Swiper(container, {
      modules: [Autoplay, FreeMode],

      // Apply continuous scroll layout for platform logos
      ...(isPlatformLogos && {
        slidesPerView: 'auto',
        spaceBetween: 20,
        loop: true,
        loopAdditionalSlides: 20,
        loopAdditionalSlides: 20,
        loopedSlidesLimit: false,
        freeMode: { enabled: true, momentum: false },
        autoplay: { delay: 0, disableOnInteraction: false, pauseOnMouseEnter: false },
        speed: 6000,
        allowTouchMove: false,
      }),

      // Default configuration for other swipers
      ...(!isPlatformLogos && {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: false,
        grabCursor: true,

        // Responsive breakpoints
        breakpoints: {
          320: {
            slidesPerView: 1,
            spaceBetween: 20
          },
          768: {
            slidesPerView: 2,
            spaceBetween: 30
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30
          }
        },
      }),
    });
  });
});

