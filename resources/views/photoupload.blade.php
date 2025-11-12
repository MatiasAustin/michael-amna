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
            <h1>Upload Your Best Picture</h1>
            <h4>Share your favorite moments with us!</h4>

        </div>

        <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
            @csrf
            <input type="file" name="photo[]" multiple required>

            <button type="submit">Upload</button>
        </form>
    </section>
</body>
</html>
