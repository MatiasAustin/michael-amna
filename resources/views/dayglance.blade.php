<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Our Day at a Glance</title>
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

    /* Timeline layout */
    .glance-list {
        position: relative;
        max-width: 1080px;
        margin: 40px auto 20px;
        padding: 10px 0 20px;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .glance-list::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 2px;
        background: #7E262520;
    }
    .glance-card {
        position: relative;
        width: calc(50% - 26px);
        margin-right: auto;
        background: #FDF8F4;
        border: 1px solid #eee3dc;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        display: grid;
        grid-template-columns: 160px 1fr;
        gap: 14px;
    }
    .glance-card.reverse {
        margin-left: auto;
        margin-right: 0;
    }
    .glance-card::before {
        content: "";
        position: absolute;
        top: 18px;
        right: -33px;
        width: 16px;
        height: 16px;
        background: #F3ECDC;
        border: 4px solid #7E2625;
        border-radius: 50%;
        box-shadow: 0 0 0 4px #FDF8F4;
    }
    .glance-card.reverse::before {
        left: -33px;
        right: auto;
    }
    .glance-photo {
        border-radius: 12px;
        overflow: hidden;
        background: #f1e5dc;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .glance-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .glance-photo.placeholder {
        color: #a08a7c;
        font-size: 14px;
    }
    .glance-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #7E2625;
    }
    .glance-title .accent {
        font-weight: 600;
    }
    .glance-title .time {
        background: #7E2625;
        color: #F3ECDC;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 12px;
    }
    .glance-headline {
        margin: 6px 0 4px;
        font-size: 20px;
        color: #2f1c16;
    }
    .glance-caption {
        margin: 0;
        color: #5c4a41;
        line-height: 1.5;
    }
    @media (max-width: 900px) {
        .glance-card {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 760px) {
        .glance-list {
            padding-left: 18px;
        }
        .glance-list::before {
            left: 12px;
        }
        .glance-card {
            width: calc(100% - 36px);
            margin: 0 0 0 24px;
        }
        .glance-card::before,
        .glance-card.reverse::before {
            left: -32px;
            right: auto;
        }
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
        <a href="{{ url('/details') }}">Finer Details</a>
        <a href="{{ url('/day-at-a-glance') }}">Our Day at a Glance</a>
        <a href="{{ url('/floormap') }}">Floor Map</a>
        <a href="{{ url('/rsvp') }}">RSVP</a>
      </div>
      <button class="hamb" aria-label="Open menu" aria-controls="mPanel" aria-expanded="false"><span></span></button>
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
    <div class="item" style="margin-bottom: 24px;">
      <h4>Our Day at a Glance</h4>
      <h1>Amna drawing of timeline with our photos in sequence</h1>
      <p style="max-width: 720px; margin: 0 auto; text-transform: none;">
        A photo-led rundown of the day. Replace each placeholder with your chosen shots in the admin panel.
      </p>
    </div>

    <div class="glance-list">
      @foreach($items as $item)
        @php
          $timeLabel = data_get($item, 'time_label');
          $headline = data_get($item, 'headline');
          $caption = data_get($item, 'caption');
          $photoPath = data_get($item, 'photo_path');
          $photoUrl = $photoPath ? asset($photoPath) : null;
        @endphp
        <article class="glance-card {{ $loop->even ? 'reverse' : '' }}">
          <div class="glance-photo {{ $photoUrl ? '' : 'placeholder' }}">
            @if($photoUrl)
              <img src="{{ $photoUrl }}" alt="{{ $headline }}">
            @else
              <span>No photo yet</span>
            @endif
          </div>
          <div class="glance-copy">
            <div class="glance-title">
              <span class="accent">Next to</span>
              <span class="time">{{ $timeLabel }}</span>
            </div>
            <h3 class="glance-headline">{{ $headline }}</h3>
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
