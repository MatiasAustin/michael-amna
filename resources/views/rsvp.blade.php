<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ava & Mateo — RSVP</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  <link rel="icon" href="{{ asset('media/MA-favicon-beige.png') }}" type="image/png">

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
  <nav class="nav">
    <div class="nav-inner">
      <div class="brand">
        <a href="{{ url('/') }}">
          <img src="{{ asset('media/MA-favicon-beige.png') }}" alt="">
        </a>
      </div>
      <div class="links">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/details') }}">Finer Details</a>
        <a href="{{ url('/day-at-a-glance') }}">Our Day at a Glance</a>
        <a href="{{ route('floormap') }}">Floor Map</a>
        <a href="{{ url('/rsvp') }}">RSVP</a>
      </div>
      <button class="hamb" aria-label="Open menu" aria-controls="mPanel" aria-expanded="false"><span></span></button>
    </div>
    <div id="mPanel" class="panel">
      <button class="close-btn" aria-label="Close menu" aria-controls="mPanel">&times;</button>
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/details') }}">Finer Details</a>
      <a href="{{ url('/day-at-a-glance') }}">Our Day at a Glance</a>
      <a href="{{ route('floormap') }}">Floor Map</a>
      <a href="{{ url('/rsvp') }}">RSVP</a>
    </div>
  </nav>

  <section class="simple">
    <div class="item">
        <h4>WE CAN’T WAIT TO CELEBRATE WITH YOU</h4>
        <p>
            Please let us know if you’ll be joining us for the magic.<br>
            Kindly respond by 14 of March 2026.
        </p>
    </div>

    {{-- RSVP FORM --}}
    <form action="{{ route('rsvp.store') }}" method="POST" id="rsvpForm">
        @csrf
        {{-- tamu utama / contact --}}
        <input name="full_name"  placeholder="Your Full Name" required>
        <input name="email"      placeholder="Email (optional)" type="email">

        <fieldset class="attendance">
            <legend style="margin-bottom: 20px;">Are you attending?</legend>
            <label class="radio">
                <input class="radio" type="radio" name="attend" value="yes" required>
                Accept with pleasure
            </label>
            <label class="radio">
                <input class="radio" type="radio" name="attend" value="no" required>
                Decline with regret
            </label>
        </fieldset>

        <h5 style="margin-top: 40px;">Additional Guests</h5>
        <div id="guestList" style="display: flex; flex-direction: column; gap: 20px;">
            {{-- row template pertama --}}
            <div class="guest-row" data-index="0">
                <input name="guests[0][full_name]" placeholder="Guest Full Name" required>
                <input name="guests[0][email]"     placeholder="Guest Email (optional)" type="email">
                <button type="button" class="remove">Remove</button>
            </div>
        </div>
        <button type="button" id="addGuest">+ Add another guest</button>

        <h5 style="margin-top: 40px;">Message for the couple (optional)</h5>
        <textarea name="message" rows="3" placeholder="Write a short note…"></textarea>

        <button type="submit">I DO</button>
    </form>

    {{-- JS untuk dynamic guest rows (pastikan file ini cuma ngurus tambah/hapus guest, bukan submit) --}}
    <script src="{{ asset('js/rsvp-form.js') }}"></script>

    {{-- POPUP --}}
    <div id="successPopup" class="popup" style="
        display:none;
        position:fixed;
        inset:0;
        z-index:9999;
        justify-content:center;
        background-color: #0000009c;
        align-items:center;
        margin:0 auto;
    ">
        <div style="background:#F3ECDC; padding:20px 26px;max-width:320px; text-align:center;
        border-radius: 20px;
        border 1px solid #3d1516;">
            <p style="margin:0 0 12px; color:#3d1516;">Thank you! Your RSVP has been recorded.</p>
            <button id="closePopupBtn" style="padding:5px 30px;border:0;background:#3d1516;color:#F3ECDC;cursor:pointer">Close</button>
        </div>
    </div>

    <script>
        // popup basic
        const popupEl = document.getElementById('successPopup');
        function openPopup(){ popupEl.style.display = 'flex'; }
        function closePopup(){ popupEl.style.display = 'none'; }
        document.getElementById('closePopupBtn')?.addEventListener('click', closePopup);
    </script>

    {{-- kalau session success ada, buka popup setelah redirect --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const popup = document.getElementById('successPopup');
                if (popup) popup.style.display = 'flex';
            });
        </script>
    @endif

    <div style="margin-top: 60px; width: 100%; max-width: 900px; border-radius: 16px; overflow: hidden;">
        <video autoplay muted loop playsinline preload="auto" style="width:100%; height:auto; display:block; border-radius:16px;">
            <source src="{{ asset('media/michael-amna-video.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
  </section>

  <footer>All Right Reserved by @Freellab2025</footer>

  <script>
    /// General
    const hamb  = document.querySelector('.hamb');
    const panel = document.getElementById('mPanel');

    function toggle(){
      const open = panel.classList.toggle('open');
      hamb.setAttribute('aria-expanded', open);
      document.body.style.overflow = open ? 'hidden' : '';
    }

    hamb.addEventListener('click', toggle);
    panel.querySelectorAll('a').forEach(a => a.addEventListener('click', toggle));
    window.addEventListener('keydown', e => {
        if (e.key === 'Escape' && panel.classList.contains('open')) toggle();
    });

    // tombol close (X) di panel
    const closeBtn2 = document.querySelector('.close-btn');
    closeBtn2.addEventListener('click', () => {
        panel.classList.remove('open');
        hamb.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    });
  </script>

  <script src="{{ asset('js/custom.js') }}"></script>
  <script src="{{ asset('js/hum-menu.js') }}"></script>
</body>
</html>
