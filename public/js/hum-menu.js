// Humberger menu
const hamb = document.querySelector('.hamb');
const panel = document.getElementById('mPanel');
const closeBtn = document.querySelector('.close-btn');

if (hamb && panel) {
    const closePanel = () => {
        panel.classList.remove('open');
        hamb.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    };

    const toggle = () => {
        const open = panel.classList.toggle('open');
        hamb.setAttribute('aria-expanded', open ? 'true' : 'false');
        document.body.style.overflow = open ? 'hidden' : '';
    };

    hamb.addEventListener('click', toggle);
    panel.querySelectorAll('a').forEach(a => a.addEventListener('click', closePanel));
    window.addEventListener('keydown', e => { if(e.key === 'Escape' && panel.classList.contains('open')) closePanel(); });

    if (closeBtn) {
        closeBtn.addEventListener('click', closePanel);
    }
}
