import './bootstrap';
import { gsap } from 'gsap';

document.addEventListener('DOMContentLoaded', function() {
    gsap.to('.page-content', {
        opacity: 1,
        y: 0,
        duration: 0.5,
        ease: 'power2.out'
    });
});
