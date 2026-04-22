import './bootstrap';

// Library System Enhancements - Pink+Blue Complex Animations
document.addEventListener('DOMContentLoaded', () => {
    // Particles integration already in layout
    
    // Enhanced AOS for stagger effects
    AOS.init({
        duration: 1200,
        easing: 'ease-out-cubic',
        once: false,
        mirror: true,
        offset: 50
    });

    // GSAP Timeline for dashboard cards
    if (document.querySelector('.card')) {
        gsap.from('.card', {
            duration: 1.2,
            y: 100,
            opacity: 0,
            rotationX: -15,
            stagger: 0.2,
            ease: 'back.out(1.7)'
        });
    }

    // Sidebar interactions
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        // Auto-hide on route change (SPA-like)
        window.addEventListener('popstate', () => sidebar.classList.remove('active'));
    }

    // Form loading states
    document.querySelectorAll('form button[type=submit]').forEach(btn => {
        btn.addEventListener('click', function() {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
            this.disabled = true;
        });
    });

    // Badge pulse animation
    document.querySelectorAll('.badge-status').forEach(badge => {
        gsap.to(badge, {
            scale: 1.05,
            duration: 0.5,
            repeat: -1,
            yoyo: true,
            ease: 'power2.inOut'
        });
    });

    console.log('✅ Perpustakaan Complex UI Loaded - Pink+Blue Edition');
});
