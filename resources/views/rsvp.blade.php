<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ava & Mateo — RSVP</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

  <link rel="icon" href="{{ asset('media/anm-logo.png') }}" type="image/png">


  <style>
    .nav {
        backdrop-filter: blur(0px);

    }
    .brand img {
            mix-blend-mode: screen;
        }

    .hamb span::before, .hamb span::after {
        content: ""; position: absolute; left: 0;
        width: 18px; height: 1px; background: #3B1B0E;
        transition: all 0.3s ease;}
  </style>
</head>
<body>
  <nav class="nav">
    <div class="nav-inner">
      <div class="brand">
        <img src="{{ asset('media/anm-logo.png') }}" alt="">
      </div>
      <div class="links">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/details') }}">Details</a>
        <a href="{{ url('/rsvp') }}">RSVP</a>
      </div>
      <button class="hamb" aria-label="Open menu" aria-controls="mPanel" aria-expanded="false"><span style="background: #3B1B0E;"></span></button>
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
        <h1>NOW TELL US WHO’S COMING.</h1>
        <h4>RSVP BY 18 SEPTEMBER 2025<br>
            IT’S ADULTS ONLY — SO PLEASE BOOK THE BABY SITTER.</h4>

    </div>


    {{-- RSVP FORM --}}
    <form action="{{ route('rsvp.store') }}" method="POST" id="rsvpForm">
        @csrf

        <h4>Reservation Contact</h4>
        {{-- tamu utama / contact --}}
        <input name="full_name"  placeholder="Your full name" required>
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

        <h4 style="margin-top: 40px;">Additional Guests</h4>
        <div id="guestList" style="display: flex; flex-direction: column; gap: 20px;">
            {{-- row template pertama --}}
            <div class="guest-row" data-index="0">
                <input name="guests[0][full_name]" placeholder="Guest full name" required>
                <input name="guests[0][email]"     placeholder="Guest email (optional)" type="email">
                <button type="button" class="remove">Remove</button>
            </div>
        </div>
        <button type="button" id="addGuest">+ Add another guest</button>

        <h4 style="margin-top: 40px;">Message for the couple (optional)</h4>
        <textarea name="message" rows="3" placeholder="Write a short note…"></textarea>

        <button type="submit">'I DO'</button>
    </form>

    <script src="{{ asset('js/rsvp-form.js') }}"></script>

    {{-- POPUP --}}

    <div id="successPopup" class="popup" style="display:none;
    position:fixed;inset:0;z-index:9999;justify-content:center;align-items:center;background:rgba(0,0,0,.6); margin: 0 auto;">
        <div style="background:#ffffffdc;color:#333;padding:20px 26px;max-width:320px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.25)">
            <p style="margin:0 0 12px; font-family: 'poppins';">Thank you! Your RSVP has been recorded.</p>
            <button id="closePopupBtn" style="padding:5px 30px;border:0;background:#df1e29;color:#fff;cursor:pointer">Close</button>
        </div>
    </div>
    <script>
        // popup
        const popupEl = document.getElementById('successPopup');
        function openPopup(){ popupEl.style.display = 'flex'; }
        function closePopup(){ popupEl.style.display = 'none'; }
        document.getElementById('closePopupBtn')?.addEventListener('click', closePopup);

        // submit
        const form = document.getElementById('rsvpForm');
        const btn  = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            btn.disabled = true; btn.textContent = 'Sending...';

            try {
            // Kirim sebagai FormData + no-cors (tidak ada preflight)
            await fetch(form.action, {
                method: 'POST',
                mode: 'no-cors',
                body: new FormData(form)
            });

            // anggap sukses (karena no-cors kita tak bisa baca respons)
            form.reset();
            openPopup();
            } catch (err) {
            alert('Network error: ' + err.message);
            } finally {
            btn.disabled = false; btn.textContent = "'I Do'";
            }
        });
    </script>

    <iframe width="560" height="315" src="https://www.youtube.com/embed/3wDnIk5tuwY?si=DiGfxUGE2-Sxleod" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
  </section>

  <footer>All Right Reserved by @Freellab2025</footer>

  <script>
    ///General
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
    const closeBtn2 = document.querySelector('.close-btn');
    // Close menu kalau tombol X diklik
    closeBtn2.addEventListener('click', () => {
    panel.classList.remove('open');
    });
  </script>

  <script src="{{ asset('js/custom.js') }}"></script>

  <script src="{{ asset('js/hum-menu.js') }}"></script>
</body>
</html>
