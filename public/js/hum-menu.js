// Humberger menu
        const hamb = document.querySelector('.hamb');
        const panel = document.getElementById('mPanel');
        function toggle(){
        const open = panel.classList.toggle('open');
        hamb.setAttribute('aria-expanded', open);
        document.body.style.overflow = open ? 'hidden':'';
        }
        hamb.addEventListener('click', toggle);
        panel.querySelectorAll('a').forEach(a=>a.addEventListener('click', toggle));
        window.addEventListener('keydown', e=>{ if(e.key==='Escape' && panel.classList.contains('open')) toggle(); });

        // Dapatkan tombol close
        const closeBtn = document.querySelector('.close-btn');
        // Close menu kalau tombol X diklik
        closeBtn.addEventListener('click', () => {
        panel.classList.remove('open');
        });
