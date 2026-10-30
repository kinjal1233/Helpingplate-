/* HelpingPlate shared public interactions */
document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.getElementById('menuBtn');
    const navigation = document.getElementById('hpNavLinks') || document.querySelector('.hp-nav-links');

    if (menuButton && navigation) {
        if (!navigation.id) navigation.id = 'hpNavLinks';
        menuButton.setAttribute('aria-controls', navigation.id);

        const closeMenu = () => {
            navigation.classList.remove('show');
            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.setAttribute('aria-label', 'Open menu');
        };

        menuButton.addEventListener('click', (event) => {
            event.preventDefault();
            const open = !navigation.classList.contains('show');
            navigation.classList.toggle('show', open);
            menuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
            menuButton.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        });

        navigation.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeMenu();
        });

        document.addEventListener('click', (event) => {
            if (!navigation.contains(event.target) && !menuButton.contains(event.target)) closeMenu();
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 760) closeMenu();
        });
    }

    const animatedItems = document.querySelectorAll('.home-process-card, .home-feature-card');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        animatedItems.forEach((item) => {
            item.classList.add('will-animate');
            observer.observe(item);
        });
    }
});
