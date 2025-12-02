<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Michael & Amna — RSVP</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/photoupload.css') }}" />

  <link rel="icon" href="{{ asset('media/MA-favicon-beige.png') }}" type="image/png">


  <style>
    .nav {
        backdrop-filter: blur(0px);

    }
    .brand img {
            mix-blend-mode: screen;
        }

    .hamb span::before, .hamb span::after {
        content: ""; position: absolute; left: 0;
        width: 18px; height: 1px; background: #2b0f10;
        transition: all 0.3s ease;}

    .flash-toast {
        position: fixed;
        top: 18px;
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
        padding: 12px 16px;
        border-radius: 10px;
        color: #F3ECDC;
        background: #1a7f37;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        font-size: 14px;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease, transform 0.25s ease;
        min-width: 240px;
        text-align: center;
    }
    .flash-toast.error { background: #b00020; }
    .flash-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.65);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 12000;
    }
    .loading-overlay.show { display: flex; }
    .loading-spinner {
        width: 64px;
        height: 64px;
        border: 4px solid rgba(243,236,220,0.25);
        border-top-color: #F3ECDC;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body style="background-color: #F3ECDC; color: black;">
  @if (session('success'))
    <div id="flash-toast" class="flash-toast" data-type="success">
        {{ session('success') }}
    </div>
    <script>
      // Redirect to home and scroll to gallery after successful upload
      window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
          window.location.href = "{{ url('/') }}#gallery";
        }, 600);
      });
    </script>
  @elseif (session('error'))
    <div id="flash-toast" class="flash-toast error" data-type="error">
        {{ session('error') }}
    </div>
  @elseif ($errors->any())
    <div id="flash-toast" class="flash-toast error" data-type="error">
        {{ $errors->first() }}
    </div>
  @endif

  <nav class="nav">
    <div class="nav-inner">
      <div class="brand">
        <a href="{{ url('/') }}">
          <img src="{{ asset('media/MA-favicon-red.png') }}" alt="">
        </a>
      </div>
      <div class="links">
        <a href="{{ url('/') }}" style="color: #3d1516;">Home</a>
        <a href="{{ url('/details') }}" style="color: #3d1516;">Finer Details</a>
        <a href="{{ url('/day-at-a-glance') }}" style="color: #3d1516;">Our Day at a Glance</a>
        <a href="{{ route('floormap') }}" style="color: #3d1516;">Floor Map</a>
        <a href="{{ url('/rsvp') }}" style="color: #3d1516;">RSVP</a>
      </div>
      <button class="hamb" aria-label="Open menu" aria-controls="mPanel" aria-expanded="false"><span style="background: #2b0f10;"></span></button>
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
            <h1>Upload Your Best Picture</h1>
            <h4>Show us your best photos from the night!</h4>
        </div>

        <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
            @csrf
            <input id="photoInput" type="file" name="photo[]" multiple required accept="image/jpeg, image/png, image/webp">


            <p id="errorMsg" style="color:red; font-size:14px; margin-top:5px;"></p>
            <div id="previewContainer" style="margin-top: 10px;"></div>

            <button class="upload-photo" type="submit">
                Upload Photos
            </button>
        </form>
    </section>
    <div class="loading-overlay" id="uploadOverlay">
        <div style="display:flex; flex-direction:column; align-items:center; gap:14px;">
            <div class="loading-spinner" aria-label="Uploading"></div>
            <p style="margin:0; color:#F3ECDC; font-size:14px; letter-spacing:0.5px; text-align:center;">
                Uploading & compressing to under 5MB. This may take a moment if your files are large.
            </p>
        </div>
    </div>


    <script>
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('previewContainer');
        const errorMsg = document.getElementById('errorMsg');
        const maxSize = 20 * 1024 * 1024; // 20MB per file (before backend compresses to <5MB)
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const maxFiles = 3;
        const overlay = document.getElementById('uploadOverlay');
        const form = document.querySelector('form.upload-form');

        input.addEventListener('change', function () {
            preview.innerHTML = '';
            errorMsg.textContent = '';

            const files = Array.from(this.files);

            if (files.length > maxFiles) {
                errorMsg.textContent = `You can only upload up to ${maxFiles} photos.`;
                return input.value = '';
            }

            files.forEach(file => {
                if (file.size > maxSize) {
                    errorMsg.textContent = 'One of the files is too large. Maximum allowed size is 20MB per photo.';
                    return input.value = '';
                }

                if (!allowedTypes.includes(file.type)) {
                    errorMsg.textContent = 'Unsupported file format. Only JPG, PNG, and WEBP are allowed.';
                    return input.value = '';
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '140px';
                    img.style.margin = '10px';
                    img.style.borderRadius = '10px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });

        form?.addEventListener('submit', function() {
            overlay?.classList.add('show');
        });
    </script>

    <script>
      const toast = document.getElementById('flash-toast');
      if (toast) {
        const type = toast.dataset.type;
        if (type === 'error') {
          toast.classList.add('error');
        }
        // trigger entrance
        requestAnimationFrame(() => toast.classList.add('show'));
        // auto hide
        setTimeout(() => toast.classList.remove('show'), 4000);
      }
    </script>

    <script src="{{ asset('js/hum-menu.js') }}"></script>


</body>
</html>
