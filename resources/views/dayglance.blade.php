<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Michael & Amna - Our Day at a Glance</title>
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
  <nav class="nav" style="backdrop-filter: blur(0px);">
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

  <section class="simple" style="margin-bottom: 120px">
    <div class="item" style="margin-bottom: 24px;">
      <h2>Our Day at a Glance</h2>
    </div>

    <div class="glance-list">
      @foreach($items as $item)
        @php
          $timeLabel = data_get($item, 'time_label');
          $headline = data_get($item, 'headline');
          $caption = data_get($item, 'caption');
          $photoPath = data_get($item, 'photo_path');
          $photoUrl = $photoPath ? asset($photoPath) : null;
          $num = $loop->iteration;
        @endphp
        <article class="glance-card {{ $loop->even ? 'reverse' : '' }}">
          <div class="glance-photo {{ $photoUrl ? '' : 'placeholder' }}">
            @if($photoUrl)
              <img src="{{ $photoUrl }}" alt="Photo {{ $num }} - {{ $headline }}">
            @else
              <span>Photo {{ $num }}</span>
            @endif
          </div>
          <div class="glance-copy">
            <div class="glance-title">
              {{-- <span class="photo-label">{{ $num }}</span> --}}
              {{-- <span class="accent">Next to</span> --}}
              <span class="time">{{ $timeLabel }}</span>
            </div>
            <h2 class="glance-headline">{{ $headline }}</h2>
            @if(!empty($caption))
              <p class="glance-caption"><em>{{ $caption }}</em></p>
            @endif
          </div>
        </article>
      @endforeach
    </div>
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

    const closeBtn = document.querySelector('.close-btn');
    closeBtn.addEventListener('click', () => {
      panel.classList.remove('open');
      hamb.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    });
  </script>

  <script src="{{ asset('js/hum-menu.js') }}"></script>
</body>
</html>
