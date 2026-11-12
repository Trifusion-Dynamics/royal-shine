import './bootstrap';

import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

window.Alpine = Alpine;
window.Swiper = Swiper;

Alpine.start();

document.addEventListener('alpine:init', () => {
    Alpine.data('mobileMenu', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    }));
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.swiper').forEach((el) => {
        const opts = {
            loop: el.dataset.loop === 'true',
            autoplay: el.dataset.autoplay === 'true'
                ? { delay: 4000, disableOnInteraction: false }
                : false,
            pagination: el.querySelector('.swiper-pagination')
                ? { el: el.querySelector('.swiper-pagination'), clickable: true }
                : false,
            navigation: el.querySelector('.swiper-button-next')
                ? {
                    nextEl: el.querySelector('.swiper-button-next'),
                    prevEl: el.querySelector('.swiper-button-prev'),
                }
                : false,
            slidesPerView: 1,
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        };
        new Swiper(el, opts);
    });
});
