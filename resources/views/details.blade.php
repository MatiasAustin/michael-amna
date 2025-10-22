<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ava & Mateo — Details</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  <style>
    .nav {
        backdrop-filter: blur(0px);
    }
    .brand img {
            mix-blend-mode: screen;
        }
  </style>
</head>
<body>
  <nav class="nav" style="backdrop-filter: blur(0px);">
    <div class="nav-inner">
      <div class="brand">
        <img src="{{ asset('media/anm-logo.png') }}" alt="">
      </div>
      <div class="links">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/details') }}">Details</a>
        <a href="{{ url('/rsvp') }}">RSVP</a>
      </div>
      <button class="hamb" aria-label="Open menu" aria-controls="mPanel" aria-expanded="false"><span></span></button>
    </div>
    <div id="mPanel" class="panel">
      <button class="close-btn" aria-label="Close menu" aria-controls="mPanel">&times;</button>
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/details') }}">Details</a>
      <a href="{{ url('/rsvp') }}">RSVP</a>
    </div>
  </nav>


  <section class="simple">

      <div class="item">
          <h4>Saturday</h4>
          <h1> 25 OCTOBER 2025</h1>
      </div>
      <div class="item">
          <h4>CEREMONY — THE PROMISE</h4>
          <h1>2PM<br>
            The Cathedral of the Annunciation of Our Lady<br>
            Surry Hills, NSW</h1>
      </div>
      <div class="item">
          <h4>DINNER & PARTY — THE PROOF</h4>
          <h1>6PM<br>
            Machine Hall<br>
            Clarence St, Sydney CBD</h1>
      </div>
      <div class="item">
          <h4>DRESS CODE</h4>
          <h1>Formal Black-Tie Optional</h1>
          <h4>(Dress like you’re entering a vogue dinner, and might end up on a dance floor in Beirut)</h4>
      </div>

      <img src="../../media/The+Garcias+Inline.webp" alt="" class="gracias">
    </section>

  <footer>All Right Reserved by @Freellab2025</footer>

  <script>
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

  </script>

  <script src="{{ asset('js/hum-menu.js') }}"></script>
</body>
</html>
