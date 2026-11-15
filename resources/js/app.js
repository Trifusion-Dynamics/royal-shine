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
        const slideCount = el.querySelectorAll('.swiper-slide').length;
        const canMove = slideCount > 1;
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const opts = {
            loop: canMove && el.dataset.loop === 'true',
            effect: el.dataset.effect || 'slide',
            fadeEffect: { crossFade: true },
            autoplay: canMove && !reduceMotion && el.dataset.autoplay === 'true'
                ? { delay: 4500, disableOnInteraction: false, pauseOnMouseEnter: true }
                : false,
            watchOverflow: true,
            observer: true,
            observeParents: true,
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
    const revealElements = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        revealElements.forEach((el) => el.classList.add('visible'));
    } else {
        const observerOptions = { threshold: 0.12, rootMargin: '0px 0px -60px 0px' };
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);
        revealElements.forEach((el) => revealObserver.observe(el));
    }
});
