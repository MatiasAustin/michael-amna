<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ava & Mateo — RSVP</title>
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
        width: 18px; height: 1px; background: #3B1B0E;
        transition: all 0.3s ease;}
  </style>
</head>
<body style="background-color: #F3ECDC; color: black;">
  <nav class="nav">
    <div class="nav-inner">
      <div class="brand">
        <img src="{{ asset('media/MA-favicon-red.png') }}" alt="">
      </div>
      <div class="links">
        <a href="{{ url('/') }}" style="color: #3B1B0E;">Home</a>
        <a href="{{ url('/details') }}" style="color: #3B1B0E;">Finer Details</a>
        <a href="{{ url('/day-at-a-glance') }}" style="color: #3B1B0E;">Our Day at a Glance</a>
        <a href="{{ url('/floormap') }}" style="color: #3B1B0E;">Floor Map</a>
        <a href="{{ url('/rsvp') }}" style="color: #3B1B0E;">RSVP</a>
      </div>
      <button class="hamb" aria-label="Open menu" aria-controls="mPanel" aria-expanded="false"><span style="background: #3B1B0E;"></span></button>
    </div>
    <div id="mPanel" class="panel">
      <button class="close-btn" aria-label="Close menu" aria-controls="mPanel">&times;</button>
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/details') }}">Finer Details</a>
      <a href="{{ url('/day-at-a-glance') }}">Our Day at a Glance</a>
      <a href="{{ url('/floormap') }}">Floor Map</a>
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


    <script>
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('previewContainer');
        const errorMsg = document.getElementById('errorMsg');
        const maxSize = 20 * 1024 * 1024; // 20MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const maxFiles = 3;

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
    </script>

    <script src="{{ asset('js/hum-menu.js') }}"></script>


</body>
</html>
