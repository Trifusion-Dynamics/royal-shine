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
            effect: el.dataset.effect || 'slide',
            crossfade: true,
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
        };
        new Swiper(el, opts);
    });

    // Scroll-triggered reveal for .reveal elements
    const observerOptions = { threshold: 0.12, rootMargin: '0px 0px -60px 0px' };
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    document.querySelectorAll('.reveal').forEach((el) => revealObserver.observe(el));
});
