
function initOffcanvas() {
	const hamburger = document.getElementById('hamburger');
	const offcanvas = document.getElementById('offcanvas');
	const overlay = document.getElementById('offcanvasOverlay');
	const links = document.querySelectorAll('.offcanvas-nav a');
	if (!hamburger || !offcanvas || !overlay) return;

	const open = () => {
		hamburger.classList.add('active');
		offcanvas.classList.add('active');
		overlay.classList.add('active');
		document.body.style.overflow = 'hidden';
	};
	const close = () => {
		hamburger.classList.remove('active');
		offcanvas.classList.remove('active');
		overlay.classList.remove('active');
		document.body.style.overflow = '';
	};

	hamburger.addEventListener('click', () => offcanvas.classList.contains('active') ? close() : open());
	overlay.addEventListener('click', close);
	links.forEach(l => l.addEventListener('click', close));
	document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
	window.addEventListener('resize', () => { if (window.innerWidth > 800) close(); });
}

document.addEventListener('DOMContentLoaded', initOffcanvas);
