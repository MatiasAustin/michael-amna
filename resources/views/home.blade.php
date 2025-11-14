<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <link rel="icon" href="{{ asset('media/anm-logo.png') }}" type="image/png">

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
      <button class="hamb" aria-label="Open menu" aria-controls="mPanel" aria-expanded="false"><span></span></button>
    </div>
    <div id="mPanel" class="panel">
      <button class="close-btn" aria-label="Close menu" aria-controls="mPanel">&times;</button>
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/details') }}">Details</a>
      <a href="{{ url('/rsvp') }}">RSVP</a>
    </div>
  </nav>

  <header>
    <video autoplay muted loop playsinline preload="auto">
      <source src="{{ asset('media/dark-wine.mp4') }}" type="video/mp4">
      Your browser does not support the video tag.
    </video>
    <div class="veil"></div>
    <!-- <div class="hero-txt">
      <h1 class="names">Ava & Mateo</h1>
      <p class="tag">Not a tradition—our tradition. Food, music, and the people we love.</p>
      <p class="date">Saturday · 18 April 2026 · Bandung</p>
    </div> -->
  </header>

  <section class="simple" style="text-align: center; margin-top: 60px; background-image: url({{ asset('media/anm-pattern-bg.png') }}); background-size: contain; background-repeat: no-repeat; background-position: center; background-size: 50% auto;">
      <h4>Not Just a Celebration</h4>
      <h1>A Story of 2 Hearts</h1>
      <p>Bound by Love, Laughter, and the memories we create together.</p>
      <h4>MORE LOVE.<br>MORE EVERYTHING.</h4>

    <div class="countdown" style="display: flex; justify-content: center; gap: 20px; margin: 40px 0 80px 0;">
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

    <h4>Together with their family</h4>
    <h1>Amma & Michael</h1>
    <p>Invite you to join in the joy and begin a new chapter of forever.</p>
    <br>
    <h4>More love. More Moments. More Everything.</h4>

      <a class="btn" href="{{ url('/details') }}" style="margin-bottom: 20px;">See Details</a>
      <a class="btn" style="color:#F3ECDC;margin-left:8px" href="{{ url('/rsvp') }}">RSVP</a>
  </section>


<section class="wishes-section">
    <h1 class="wishes-heading">Wishes From Our Loved Ones</h1>
    <p class="wishes-subtitle">
        Small notes, big love. Here are some of the words shared for our day.
    </p>

    @if ($wishes->count())
        <div class="wishes-scroller">
            <div class="wishes-row">
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
                            <img src="{{ asset('storage/' . $photo->filename) }}" alt="">
                        </div>
                    @endforeach

                    {{-- duplicate for seamless looping --}}
                    @foreach($randomTop as $photo)
                        <div class="photo-card">
                            <img src="{{ asset('storage/' . $photo->filename) }}" alt="">
                        </div>
                    @endforeach
                </div>

                {{-- ROW 2 --}}
                <div class="row-slider reverse" id="rowBottom">
                    @foreach($randomBottom as $photo)
                        <div class="photo-card">
                            <img src="{{ asset('storage/' . $photo->filename) }}" alt="">
                        </div>
                    @endforeach

                    {{-- duplicate --}}
                    @foreach($randomBottom as $photo)
                        <div class="photo-card">
                            <img src="{{ asset('storage/' . $photo->filename) }}" alt="">
                        </div>
                    @endforeach
                </div>

            </div>
        @else
            <p class="text-center text-muted" style="width: 100%; margin: 0 auto; text-align: center;">Belum ada foto.</p>
        @endif
      </div>
  <div class="button-cont">
    <button class="btn" style="margin-top: 20px;" onclick="window.location.href='{{ url('/photoupload') }}'">Upload Your Best Picture</button>
  </div>

  <footer>All Right Reserved by @Freellab2025</footer>


    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

    <script src="{{ asset('js/hum-menu.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
          const allPhotos = [...document.querySelectorAll(".photo-card")];

          if (allPhotos.length === 0) return;

          // Shuffle (Fisher-Yates)
          function shuffle(arr) {
              let i = arr.length;
              while (i > 0) {
                  const j = Math.floor(Math.random() * i--);
                  [arr[i], arr[j]] = [arr[j], arr[i]];
              }
              return arr;
          }

          const shuffled = shuffle([...allPhotos]);

          // Ambil 5 random untuk masing-masing baris
          const top5 = shuffled.slice(0, 5);
          const bottom5 = shuffled.slice(5, 10);

          const rowTop = document.getElementById("rowTop");
          const rowBottom = document.getElementById("rowBottom");

          // Kosongkan row
          rowTop.innerHTML = "";
          rowBottom.innerHTML = "";

          // Masukkan 5 photo
          top5.forEach(p => rowTop.appendChild(p.cloneNode(true)));
          bottom5.forEach(p => rowBottom.appendChild(p.cloneNode(true)));

          // Duplikasi untuk loop animasi
          top5.forEach(p => rowTop.appendChild(p.cloneNode(true)));
          bottom5.forEach(p => rowBottom.appendChild(p.cloneNode(true)));

          // Tambah efek zig-zag
          rowBottom.classList.add("reverse");
      });
      </script>

      <script>
            (function () {
                const scroller = document.querySelector('.wishes-scroller');
                if (!scroller) return;

                // Biat lebih smooth & nggak cepat banget
                const speed = 0.2; // px per frame
                let pos = 0;

                // Duplikasi konten biar loopnya lebih halus
                const row = scroller.querySelector('.wishes-row');
                if (!row) return;

                // Kalau card-nya kurang dari 3, nggak usah auto slide
                const cards = row.children;
                if (cards.length < 4) return;

                const clone = row.cloneNode(true);
                scroller.appendChild(clone);

                function step() {
                pos += speed;
                // total scrollable width (2 row digabung)
                const maxScroll = row.scrollWidth;

                if (pos >= maxScroll) {
                    pos = 0; // reset ke awal
                }

                scroller.scrollLeft = pos;
                requestAnimationFrame(step);
                }

                // Start animasi
                requestAnimationFrame(step);

                // Pause animasi ketika user drag scroll (biar nggak annoying)
                let isUserScrolling = false;
                let scrollTimeout;

                scroller.addEventListener('wheel', () => {
                isUserScrolling = true;
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    isUserScrolling = false;
                }, 1500);
                }, { passive: true });

                const oldStep = step;
                function animatedStep() {
                if (!isUserScrolling) {
                    pos += speed;
                    const maxScroll = row.scrollWidth;
                    if (pos >= maxScroll) pos = 0;
                    scroller.scrollLeft = pos;
                }
                requestAnimationFrame(animatedStep);
                }
                // override ke yang bisa pause
                requestAnimationFrame(animatedStep);
            })();
            </script>





</body>
</html>
