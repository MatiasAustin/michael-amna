<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ava & Mateo — Floor Map</title>
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


    <section class="simple">


        {{-- FLOOR MAP --}}
            @if($floorMapUrl)
                <h1 style="margin-top:40px;">Floor Map</h1>

                <div id="floorMapWrapper" style="width:100%; height:500px; border:1px solid #ddd; border-radius:20px; overflow:hidden; position:relative; cursor:grab; background:#f5f5f5; touch-action:none;">

                    <img id="floorMapImage"
                        src="{{ $floorMapUrl }}"
                        alt="Floor Map"
                        style="user-select:none; -webkit-user-drag:none; max-width:none; position:absolute; top:0; left:0; transform-origin: top left;">
                </div>

                <div style="margin-bottom:10px; width:100%; max-width:800px; display:flex; justify-content:end; gap:10px; margin-top:10px; margin-bottom:10px; margin-right:10%;">
                    <button type="button" id="zoomInBtn"
                            style="padding:4px 8px; font-size:12px;">+</button>
                    <button type="button" id="zoomOutBtn"
                            style="padding:4px 8px; font-size:12px;">−</button>
                    <button type="button" id="resetBtn"
                            style="padding:4px 8px; font-size:12px;">Reset</button>
                    <button type="button" id="fullscreenBtn"
                            style="padding:4px 8px; font-size:12px;">Fullscreen</button>
                </div>

            @endif

            <script>
                (function() {
                    const wrapper  = document.getElementById('floorMapWrapper');
                    const img      = document.getElementById('floorMapImage');
                    if (!wrapper || !img) return;

                    let scale = 1;
                    let minScale = 1;
                    let originX = 0;
                    let originY = 0;
                    let isPanning = false;
                    let startX = 0;
                    let startY = 0;
                    let lastX = 0;
                    let lastY = 0;

                    function clampPosition() {
                        const w = wrapper.clientWidth;
                        const h = wrapper.clientHeight;
                        const iwScaled = (img.naturalWidth || img.width) * scale;
                        const ihScaled = (img.naturalHeight || img.height) * scale;

                        const minX = iwScaled <= w ? (w - iwScaled) / 2 : w - iwScaled;
                        const maxX = iwScaled <= w ? (w - iwScaled) / 2 : 0;
                        const minY = ihScaled <= h ? (h - ihScaled) / 2 : h - ihScaled;
                        const maxY = ihScaled <= h ? (h - ihScaled) / 2 : 0;

                        originX = Math.min(maxX, Math.max(minX, originX));
                        originY = Math.min(maxY, Math.max(minY, originY));
                        lastX = originX;
                        lastY = originY;
                    }

                    function centerImage() {
                        const w = wrapper.clientWidth;
                        const h = wrapper.clientHeight;
                        const iwScaled = (img.naturalWidth || img.width) * scale;
                        const ihScaled = (img.naturalHeight || img.height) * scale;

                        originX = (w - iwScaled) / 2;
                        originY = (h - ihScaled) / 2;
                        lastX = originX;
                        lastY = originY;
                        applyTransform();
                    }

                    function computeMinScale(center = false) {
                        const w = wrapper.clientWidth;
                        const h = wrapper.clientHeight;
                        const iw = img.naturalWidth || img.width;
                        const ih = img.naturalHeight || img.height;
                        if (!iw || !ih) return;

                        minScale = Math.min(w / iw, h / ih); // fit entire image inside frame
                        if (scale < minScale) scale = minScale;

                        if (center) {
                            const scaledW = iw * scale;
                            const scaledH = ih * scale;
                            originX = (w - scaledW) / 2;
                            originY = (h - scaledH) / 2;
                            lastX = originX;
                            lastY = originY;
                        }

                        clampPosition();
                        applyTransform();
                    }

                    function applyTransform() {
                        clampPosition();
                        img.style.transform = `translate(${originX}px, ${originY}px) scale(${scale})`;
                    }

                    // helpers buat mulai / gerak / stop pan
                    function startPan(clientX, clientY) {
                        isPanning = true;
                        wrapper.style.cursor = 'grabbing';
                        startX = clientX - lastX;
                        startY = clientY - lastY;
                    }

                    function movePan(clientX, clientY) {
                        if (!isPanning) return;
                        lastX = clientX - startX;
                        lastY = clientY - startY;
                        originX = lastX;
                        originY = lastY;
                        applyTransform();
                    }

                    function endPan() {
                        isPanning = false;
                        wrapper.style.cursor = 'grab';
                    }

                    // Zoom buttons
                    document.getElementById('zoomInBtn').addEventListener('click', () => {
                        scale *= 1.2;
                        if (scale < minScale) scale = minScale;
                        applyTransform();
                    });

                    document.getElementById('zoomOutBtn').addEventListener('click', () => {
                        scale /= 1.2;
                        if (scale < minScale) scale = minScale;
                        applyTransform();
                    });

                    document.getElementById('resetBtn').addEventListener('click', () => {
                        scale = minScale;
                        originX = 0;
                        originY = 0;
                        lastX = 0;
                        lastY = 0;
                        computeMinScale(true); // center on reset
                        centerImage();
                    });

                    // 🔹 Desktop: mouse drag
                    wrapper.addEventListener('mousedown', e => {
                        e.preventDefault();
                        startPan(e.clientX, e.clientY);
                    });

                    window.addEventListener('mousemove', e => {
                        movePan(e.clientX, e.clientY);
                    });

                    window.addEventListener('mouseup', () => {
                        endPan();
                    });

                    // 🔹 Mobile: touch drag
                    wrapper.addEventListener('touchstart', e => {
                        if (e.touches.length !== 1) return; // single finger only
                        const t = e.touches[0];
                        startPan(t.clientX, t.clientY);
                    }, { passive: true });

                    wrapper.addEventListener('touchmove', e => {
                        if (!isPanning || e.touches.length !== 1) return;
                        const t = e.touches[0];
                        movePan(t.clientX, t.clientY);
                        e.preventDefault(); // cegah scroll page
                    }, { passive: false });

                    wrapper.addEventListener('touchend', () => {
                        endPan();
                    });

                    wrapper.addEventListener('touchcancel', () => {
                        endPan();
                    });

                    // Scroll wheel zoom (desktop)
                    wrapper.addEventListener('wheel', e => {
                        e.preventDefault();
                        const delta = e.deltaY < 0 ? 1.02 : 0.98; // even slower zoom
                        scale *= delta;
                        if (scale < minScale) scale = minScale;
                        applyTransform();
                    }, { passive: false });

                    // Fullscreen
                    const fullscreenBtn = document.getElementById('fullscreenBtn');
                    fullscreenBtn.addEventListener('click', () => {
                        if (!document.fullscreenElement) {
                            wrapper.requestFullscreen?.();
                        } else {
                            document.exitFullscreen?.();
                        }
                    });

                    // initial
                    const init = () => {
                        computeMinScale(true); // center on load
                        centerImage();
                    };

                    if (img.complete) {
                        init();
                    } else {
                        img.addEventListener('load', init);
                    }

                    window.addEventListener('resize', () => computeMinScale(false));
                })();
            </script>

            {{-- END FLOOR MAP --}}

            <div class="divider" id="find" style="height: 0.5px; background-color: #F3ECDC10; margin: 20px 0; width: 100%;"></div>

        {{-- Search Bar --}}

            <h3 style="text-align:left; width: 100%; font-weight: 300;">Find Your Invitation Details</h3>
            <form action="{{ route('floormap') }}" method="GET" style="margin-bottom:20px; width: 100%; max-width:100%; min-width:100%; display:flex; gap:10px;">
                <input type="text" name="code" placeholder="Enter unique code" value="{{ request('code') }}" style="padding: 10px; width: 100%; height: auto;" required>
                <button type="submit">Search</button>
            </form>

            @if($errors->has('code'))
                <p style="color:red;">{{ $errors->first('code') }}</p>
            @endif

            {{--  RSVP & GUEST DETAILS --}}

            @if($rsvp)
            <div id="search-result"></div>
            <table style="width:100%; border-collapse:collapse; text-align: left; font-size: 10px">
                <thead style="font-weight: 300;">
                    <tr style="border-bottom:1px solid #F3ECDC;">
                        <th style="text-align:left; padding:8px;">Type</th>
                        <th style="text-align:left; padding:8px;">Name</th>
                        <th style="text-align:left; padding:8px;">Email</th>
                        <th style="text-align:left; padding:8px;">Table</th>
                        <th style="text-align:left; padding:8px;">Seat</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Row RSVP utama --}}
                    <tr>
                        <td style="padding:8px;">RSVP</td>
                        <td style="padding:8px;">{{ $rsvp->full_name }}</td>
                        <td style="padding:8px;">{{ $rsvp->email }}</td>
                        <td style="padding:8px;">{{ $rsvp->table_number ?? '-' }}</td>
                        <td style="padding:8px;">{{ $rsvp->seat_number ?? '-' }}</td>
                    </tr>

                    {{-- Row semua guest --}}
                    @foreach($rsvp->guests as $guest)
                        <tr>
                            <td style="padding:8px;">Guest</td>
                            <td style="padding:8px;">{{ $guest->full_name }}</td>
                            <td style="padding:8px;">{{ $guest->email }}</td>
                            <td style="padding:8px;">{{ $guest->table_number ?? '-' }}</td>
                            <td style="padding:8px;">{{ $guest->seat_number ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @else
                <p style="margin: 20px auto;">Please enter your unique code to view the invitation details here.</p>
            @endif

    </section>

    <footer>All Right Reserved by @Freellab2025</footer>

    {{-- Auto-scroll to results if applicable --}}

    <script>
        // If a code was searched and results exist, auto-scroll to the table
        (function() {
            const hasQuery = '{{ request("code") }}'.trim().length > 0;
            const resultEl = document.getElementById('search-result');
            const findAnchor = document.getElementById('find');

            if (hasQuery && resultEl) {
                requestAnimationFrame(() => {
                    resultEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            } else if (hasQuery && findAnchor) {
                requestAnimationFrame(() => {
                    findAnchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
        })();
    </script>

    <script>
        // Hamburger toggle for this page
        const hamb = document.querySelector('.hamb');
        const panel = document.getElementById('mPanel');
        const closeBtn = document.querySelector('.close-btn');

        function closePanel() {
            panel?.classList.remove('open');
            hamb?.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        function togglePanel() {
            const open = panel?.classList.toggle('open');
            hamb?.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.style.overflow = open ? 'hidden' : '';
        }

        if (hamb && panel) {
            hamb.addEventListener('click', togglePanel);
            panel.querySelectorAll('a').forEach(a => a.addEventListener('click', closePanel));
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closePanel);
        }

        window.addEventListener('keydown', e => {
            if (e.key === 'Escape' && panel?.classList.contains('open')) {
                closePanel();
            }
        });
    </script>
</body>
</html>
