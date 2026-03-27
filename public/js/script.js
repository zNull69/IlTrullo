document.addEventListener('DOMContentLoaded', function () {
    var btn     = document.getElementById('hamburger');
    var menu    = document.getElementById('offcanvas');
    var overlay = document.getElementById('offcanvasOverlay');

    if (!btn || !menu || !overlay) return;

    function openMenu() {
        menu.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        menu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    btn.addEventListener('click', openMenu);
    overlay.addEventListener('click', closeMenu);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });
});