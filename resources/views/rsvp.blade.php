<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ava & Mateo — RSVP</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

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
<body style="background-color: #F3ECDC; color: black;">
  <nav class="nav">
    <div class="nav-inner">
      <div class="brand">
        <img src="{{ asset('media/anm-logo-brown.png') }}" alt="">
      </div>
      <div class="links">
        <a href="{{ url('/') }}" style="color: #3B1B0E;">Home</a>
        <a href="{{ url('/details') }}" style="color: #3B1B0E;">Details</a>
        <a href="{{ url('/rsvp') }}" style="color: #3B1B0E;">RSVP</a>
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

    <form action="https://script.google.com/macros/s/AKfycbzhYDSdUpS68leHueoRKfcoeEB_L1orSkEdtQKrsSjzCjyAWdYGqty0GBKQL5YLf8E/exec" method="POST" id="rsvpForm">
        <div class="name">
            <div class="row-form">
                <label for="name">First Name</label>
                <input type="text" placeholder="" name="first" id="first" required>
            </div>
            <div class="row-form">
                <label for="name">Last Name</label>
                <input type="text" placeholder="" name="last" id="last" required>
            </div>
        </div>

        <label for="guests">NAME(S) OF Guest(s) in your Party</label>
        <input type="text" placeholder="" name="guests" id="guests" required>

        <label for="attend">SO — ARE YOU COMING?</label>
        <div class="radios" name="attend" id="attend">
            <label class="radio"><input class="radio" type="radio" name="attend" value="yes" required> Accept with pleasure</label>
            <label class="radio"><input class="radio" type="radio" name="attend" value="no" required> Decline with regret</label>
        </div>

        <br>
        <label for="wishes">DIETARY NEEDS</label>
        <textarea rows="4" placeholder="Short wishes here..." name="wishes" id="wishes" required></textarea>

        <br>
        <button type="submit">'I Do'</button>
    </form>

        <form id="uploadForm" method="POST" action="{{ route('photos.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="photo" id="photoInput" accept=".jpg,.jpeg,.png" />
            <div id="previewContainer"></div>
            <p id="errorMsg" style="color: red;"></p>
            <button type="submit">Upload</button>
            </form>
        @if ($errors->has('photo'))
        <span class="text-danger">{{ $errors->first('photo') }}</span>
        @endif



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

  <footer style="color: rgb(23, 23, 23);">All Right Reserved by @Freellab2025</footer>

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

  <script>
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('previewContainer');
        const errorMsg = document.getElementById('errorMsg');
        const maxSize = 5 * 1024 * 1024; // 5MB

        input.addEventListener('change', function () {
            preview.innerHTML = '';
            errorMsg.textContent = '';

            const file = this.files[0];
            if (!file) return;

            // Validasi ukuran
            if (file.size > maxSize) {
            errorMsg.textContent = 'Ukuran file terlalu besar. Maksimal 5MB.';
            this.value = ''; // reset input
            return;
            }

            // Validasi tipe
            const allowedTypes = ['image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
            errorMsg.textContent = 'Format file tidak didukung. Hanya JPG dan PNG.';
            this.value = '';
            return;
            }

            // Tampilkan preview
            const reader = new FileReader();
            reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '300px';
            img.style.marginTop = '10px';
            preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    </script>

  <script src="{{ asset('js/custom.js') }}"></script>

  <script src="{{ asset('js/hum-menu.js') }}"></script>
</body>
</html>
