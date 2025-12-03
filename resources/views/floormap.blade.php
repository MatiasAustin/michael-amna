<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Michael & Amna - Floor Map</title>
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
    .map-btn {
        display: inline-block;
        padding: 12px 20px;
        border-bottom: 1px solid #F3ECDC;
        color: #F3ECDC;
        background: transparent;
        font-size: 16px;
        transition: all 0.2s ease;
    }
    .map-btn:hover {
        background: #F3ECDC;
        color: #3d1516;
        border-color: #F3ECDC;
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
        <div class="item">
          <h2>Floor Map</h2>

            {{-- FLOOR MAP --}}
            @if($floorMapUrl)
                <a href="{{ $floorMapUrl }}" class="map-btn" target="_blank" rel="noopener" download>
                    View / Download Floor Map
                </a>
            @else
                <p>Floor map is not available yet.</p>
            @endif
        {{-- END FLOOR MAP --}}

        </div>

        

            <div class="divider" id="find" style="height: 0.5px; background-color: #F3ECDC10; margin: 20px 0; width: 100%;"></div>

        {{-- Search Bar --}}

            <h2 style="text-align:left; width: 100%; font-weight: 300;">Find Your Invitation Details</h2>
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
