<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <link rel="icon" href="{{ asset('media/MA-favicon-beige.png') }}" type="image/png">
    <style>
        .sound-toggle {
            position: fixed;
            right: 18px;
            bottom: 18px;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(0, 0, 0, 0.55);
            color: #f9f6ef;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(6px);
            z-index: 999;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .sound-toggle:hover { background: rgba(0, 0, 0, 0.7); transform: translateY(-1px); }
        .sound-toggle svg { width: 20px; height: 20px; }
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

  <header>
    <video id="heroVideo" autoplay loop playsinline preload="auto">
      <source src="{{ asset('media/michael-amna-video.mp4') }}" type="video/mp4">
      Your browser does not support the video tag.
    </video>
    <div class="veil"></div>
    <!-- <div class="hero-txt">
      <h1 class="names">Ava & Mateo</h1>
      <p class="tag">Not a tradition—our tradition. Food, music, and the people we love.</p>
      <p class="date">Saturday · 18 April 2026 · Bandung</p>
    </div> -->
  </header>

  <button id="soundToggle" class="sound-toggle" type="button" aria-label="Toggle sound" aria-pressed="false" data-state="off">
      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M11 5L6 9H3v6h3l5 4V5z"></path>
          <line x1="19" y1="5" x2="19" y2="19"></line>
          <line x1="16" y1="8" x2="22" y2="14"></line>
      </svg>
  </button>

  {{-- <section class="simple" style="text-align: center; margin-top: 60px; background-image: url({{ asset('media/MA-favicon-trans.png') }}); background-size: contain; background-repeat: no-repeat; background-position: center; background-opacity: 0.1;"> --}}
  <section class="simple" style="text-align: center; margin-top: 60px; background-size: contain; background-repeat: no-repeat; background-position: center; background-opacity: 0.1;">

    <div class="countdown" style="display: flex; justify-content: center;margin: 40px 0 80px 0;">
        <div class="count-box">
            <label>DAYS</label>
            <span id="days"></span>
        </div>
        <div class="count-box">
            <label>HOURS</label>
            <span id="hours"></span>
        </div>
        <div class="count-box">
            <label>MINUTES</label>
            <span id="minutes"></span>
        </div>
        <div class="count-box">
            <label>SECONDS</label>
            <span id="seconds"></span>
        </div>
    </div>

    <script>
        (function(){
        // ISO UTC dari server (null kalau belum diset)
        const deadlineISO = @json(optional(optional($countdown)->event_at_utc)->toIso8601String());


        if (!deadlineISO) return; // belum ada tanggal di DB

        const deadline = new Date(deadlineISO); // browser akan handle timezone
        const dEl = document.getElementById('days');
        const hEl = document.getElementById('hours');
        const mEl = document.getElementById('minutes');
        const sEl = document.getElementById('seconds');
        const pad = n => String(n).padStart(2,'0');

        function tick(){
            const diff = deadline - new Date();
            if (diff <= 0) {
            dEl.textContent = hEl.textContent = mEl.textContent = sEl.textContent = '00';
            clearInterval(t); return;
            }
            const sec = Math.floor(diff/1000);
            const days = Math.floor(sec/86400);
            const hrs  = Math.floor((sec%86400)/3600);
            const mins = Math.floor((sec%3600)/60);
            const secs = sec%60;

            dEl.textContent = pad(days);
            hEl.textContent = pad(hrs);
            mEl.textContent = pad(mins);
            sEl.textContent = pad(secs);
        }

        tick();
        const t = setInterval(tick, 1000);
        })();

    </script>

    <h1 style="font-family: 'Kunstler Script Local', cursive; text-transform:capitalize; font-size: 72px; margin: 0 25% 0 0;">Michael</h1>

    <h1 style="font-family: 'Kunstler Script Local', cursive; text-transform:capitalize; font-size: 72px; margin-top: -7%; margin-bottom: -3%">&</h1>

    <h1 style="font-family: 'Kunstler Script Local', cursive; text-transform:capitalize; font-size: 72px; margin: 0 0 0 15%;">Amna</h1>

    <h4 style="margin-top: 40px;">We can't wait to celebrate our special day with you.</h4>
    <p>Along with your formal invitation, please enjoy this extension filled with all the little details we've planned.</p>
    <br>
      <a class="btn" href="{{ url('/details') }}" style="margin-bottom: 20px;">Click Here to Unveil That Magic</a>
      <a class="btn" style="margin-left:8px" href="{{ url('/rsvp') }}">RSVP</a>
  </section>




  {{-- OUR GALLERY SECTION --}}
    <div class="our-gallery">
        @if($photos->count())
            @php
                $randomTop = $photos->shuffle()->take(5);
                $randomBottom = $photos->shuffle()->take(5);
            @endphp

            <div class="dual-gallery">

                {{-- ROW 1 --}}
                <div class="row-slider" id="rowTop">
                    @foreach($randomTop as $photo)
                        <div class="photo-card">
                            <img src="{{ asset('/' . $photo->filename) }}" alt="">
                        </div>
                    @endforeach

                    {{-- duplicate for seamless looping --}}
                    @foreach($randomTop as $photo)
                        <div class="photo-card">
                            <img src="{{ asset('/' . $photo->filename) }}" alt="">
                        </div>
                    @endforeach
                </div>

                {{-- ROW 2 --}}
                <div class="row-slider reverse" id="rowBottom">
                    @foreach($randomBottom as $photo)
                        <div class="photo-card">
                            <img src="{{ asset('/' . $photo->filename) }}" alt="">
                        </div>
                    @endforeach

                    {{-- duplicate --}}
                    @foreach($randomBottom as $photo)
                        <div class="photo-card">
                            <img src="{{ asset('/' . $photo->filename) }}" alt="">
                        </div>
                    @endforeach
                </div>

            </div>
        @else
            <p class="text-center text-muted" style="width: 100%; margin: 0 auto; text-align: center;">No photos yet.</p>
        @endif
    </div>

    <div class="button-cont">
        <button class="btn" onclick="window.location.href='{{ url('/photoupload') }}'">Upload Your Best Picture</button>
    </div>


    {{-- WISHES SECTION --}}
    <section class="wishes-section">
        <h1 class="wishes-heading">Wishes From Our Loved Ones</h1>
        <p class="wishes-subtitle">
            Small notes, big love. Here are some of the words shared for our day.
        </p>

        @if ($wishes->count())
            <div class="wishes-scroller">
                <div class="wishes-track">
                    {{-- TRACK A --}}
                    @foreach ($wishes as $wish)
                        <article class="wish-card">
                            <p class="wish-text">“{{ $wish->message }}”</p>
                            <p class="wish-name">— {{ $wish->full_name }}</p>
                        </article>
                    @endforeach

                    {{-- TRACK A (CLONE) --}}
                    @foreach ($wishes as $wish)
                        <article class="wish-card">
                            <p class="wish-text">“{{ $wish->message }}”</p>
                            <p class="wish-name">— {{ $wish->full_name }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <p class="wishes-empty">
                No wishes have been written yet. Be the first one to leave a note for us.
            </p>
        @endif

        <div class="wishes-cta">
            <a href="{{ url('/rsvp') }}" class="btn">Send Your Wishes</a>
        </div>
    </section>

  <footer>All Right Reserved by @Freellab2025</footer>


    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

    <script src="{{ asset('js/hum-menu.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>


    {{-- Our Gallery JS --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll(".row-slider"); // #rowTop & #rowBottom

        if (!rows.length) return;

        rows.forEach((row) => {
            enableDragScroll(row);
            enableAutoScroll(row);
        });

        function enableDragScroll(container) {
            let isDown = false;
            let startX;
            let scrollLeft;

            // flag buat autoScroll
            container.dataset.dragging = "false";

            // Desktop: mouse drag
            container.addEventListener("mousedown", (e) => {
                isDown = true;
                container.dataset.dragging = "true";
                container.classList.add("dragging");
                startX = e.pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
            });

            container.addEventListener("mouseleave", () => {
                isDown = false;
                container.dataset.dragging = "false";
                container.classList.remove("dragging");
            });

            container.addEventListener("mouseup", () => {
                isDown = false;
                container.dataset.dragging = "false";
                container.classList.remove("dragging");
            });

            container.addEventListener("mousemove", (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - container.offsetLeft;
                const walk = x - startX;
                container.scrollLeft = scrollLeft - walk;
            });

            // Mobile: touch drag
            container.addEventListener("touchstart", (e) => {
                isDown = true;
                container.dataset.dragging = "true";
                startX = e.touches[0].pageX;
                scrollLeft = container.scrollLeft;
            }, { passive: true });

            container.addEventListener("touchend", () => {
                isDown = false;
                container.dataset.dragging = "false";
            });

            container.addEventListener("touchmove", (e) => {
                if (!isDown) return;
                const x = e.touches[0].pageX;
                const walk = x - startX;
                container.scrollLeft = scrollLeft - walk;
            }, { passive: true });
        }

        function enableAutoScroll(container) {
            const baseSpeed = 1; // speed bisa lo adjust
            const isReverse = container.classList.contains("reverse");
            const direction = isReverse ? -1 : 1;
            const speed = baseSpeed * direction;

            let isHover = false;

            // Mulai dari tengah kalau reverse biar seam-nya ketutup
            if (isReverse) {
                container.scrollLeft = container.scrollWidth / 2;
            }

            // Hover / focus -> pause
            container.addEventListener("mouseenter", () => {
                isHover = true;
            });

            container.addEventListener("mouseleave", () => {
                isHover = false;
            });

            // Sentuh di mobile -> pause juga
            container.addEventListener("touchstart", () => {
                isHover = true;
            }, { passive: true });

            container.addEventListener("touchend", () => {
                isHover = false;
            });

            function loop() {
                const isDragging = container.dataset.dragging === "true";

                if (!isHover && !isDragging) {
                    const halfWidth = container.scrollWidth / 2;

                    container.scrollLeft += speed;

                    if (!isReverse) {
                        // jalan ke kanan (atau kiri tergantung RTL)
                        if (container.scrollLeft >= halfWidth) {
                            container.scrollLeft -= halfWidth;
                        }
                    } else {
                        // reverse: jalan ke arah sebaliknya
                        if (container.scrollLeft <= 0) {
                            container.scrollLeft += halfWidth;
                        }
                    }
                }

                requestAnimationFrame(loop);
            }

            requestAnimationFrame(loop);
        }
    });
    </script>


{{-- WISHES JS --}}
<script>
(() => {
  const scroller = document.querySelector('.wishes-scroller');
  if (!scroller) return;

  const track = scroller.querySelector('.wishes-track');
  if (!track) return;

  const cards = track.children;
  if (cards.length < 4) return; // minimal 2 card × 2 track

  let baseWidth = 0;      // lebar 1 track (A saja)
  let offset = 0;         // posisi virtual dalam px
  const speed = 0.5;      // px per frame (atur sesuai selera)

  let paused = false;
  let isDragging = false;
  let startX = 0;
  let startOffset = 0;

  function measure() {
    // track = A + A → baseWidth = setengah total
    baseWidth = track.scrollWidth / 2;
  }

  measure();
  window.addEventListener('resize', measure);

  // Helper: normalisasi offset ke [0, baseWidth)
  function normalizeOffset() {
    if (!baseWidth) return;
    offset = ((offset % baseWidth) + baseWidth) % baseWidth;
  }

  // Hover / keluar (desktop)
  scroller.addEventListener('mouseenter', () => {
    paused = true;
  });

  scroller.addEventListener('mouseleave', () => {
    paused = false;
    isDragging = false;
  });

  // Mouse drag
  scroller.addEventListener('mousedown', (e) => {
    isDragging = true;
    paused = true;
    startX = e.pageX;
    startOffset = offset;
  });

  window.addEventListener('mouseup', () => {
    if (!isDragging) return;
    isDragging = false;
    paused = false;
  });

  window.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    const delta = e.pageX - startX;
    offset = startOffset - delta;
    normalizeOffset();
  });

  // Touch (mobile)
  scroller.addEventListener('touchstart', (e) => {
    isDragging = true;
    paused = true;
    startX = e.touches[0].pageX;
    startOffset = offset;
  }, { passive: true });

  scroller.addEventListener('touchmove', (e) => {
    if (!isDragging) return;
    const delta = e.touches[0].pageX - startX;
    offset = startOffset - delta;
    normalizeOffset();
  }, { passive: true });

  scroller.addEventListener('touchend', () => {
    isDragging = false;
    paused = false;
  });

  // Loop animasi
  function loop() {
    if (!paused && !isDragging && baseWidth) {
      offset += speed;       // jalan otomatis
      normalizeOffset();
    }

    track.style.transform = `translateX(${-offset}px)`;

    requestAnimationFrame(loop);
  }

  requestAnimationFrame(loop);
})();
</script>

<script>
// Hero video sound toggle
document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('heroVideo');
    const toggle = document.getElementById('soundToggle');
    if (!video || !toggle) return;

    const icons = {
        off: '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5 6 9H3v6h3l5 4V5z"></path><line x1="19" y1="5" x2="19" y2="19"></line><line x1="16" y1="8" x2="22" y2="14"></line></svg>',
        on: '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5 6 9H3v6h3l5 4V5z"></path><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path><path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path></svg>'
    };

    const setState = (muted) => {
        video.muted = muted;
        toggle.dataset.state = muted ? 'off' : 'on';
        toggle.setAttribute('aria-pressed', muted ? 'false' : 'true');
        toggle.innerHTML = muted ? icons.off : icons.on;
    };

    const tryPlayUnmute = () => {
        video.muted = false;
        const playPromise = video.play();
        if (playPromise && typeof playPromise.then === 'function') {
            playPromise.then(() => setState(false)).catch(() => setState(true));
        } else {
            setState(false);
        }
    };

    toggle.addEventListener('click', () => {
        if (video.muted) {
            tryPlayUnmute();
        } else {
            setState(true);
        }
    });

    const kickoff = () => {
        if (video.muted) {
            tryPlayUnmute();
        }
    };
    document.addEventListener('click', kickoff, { once: true });
    document.addEventListener('touchstart', kickoff, { once: true, passive: true });

    // try to start with sound immediately
    tryPlayUnmute();
    // fallback: ensure icon reflects current mute
    setState(video.muted);
});
</script>



</body>
</html>
