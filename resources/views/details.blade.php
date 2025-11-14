<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ava & Mateo — Details</title>
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


  <section class="simple">

      <div class="item">
          <h4>Saturday</h4>
        <h1>{{ optional($countdown->event_at_utc)->format('d M Y') }}</h1>
        @php
            $event = $countdown?->event_at_utc;
            // fallback values
            $evTitle = 'Ava & Mateo — The Promise';
            $evLocation = $venue?->venue_name ? $venue->venue_name . ', ' . ($venue?->venue_location_text ?? '') : 'The Cathedral of the Annunciation of Our Lady, Surry Hills, NSW';
            $evDesc = 'Ceremony — The Promise.';
            // Google Calendar needs UTC timestamp in YYYYMMDDTHHMMSSZ
            $startUtc = $event ? $event->format('Ymd\\THis\\Z') : null;
            $endUtc = $event ? $event->copy()->addHours(3)->format('Ymd\\THis\\Z') : null; // default 3 hour duration
            // URL-encoded pieces for direct links
            $gText = urlencode($evTitle);
            $gDetails = urlencode($evDesc);
            $gLocation = urlencode($evLocation);
            $googleHref = $startUtc && $endUtc
                ? "https://www.google.com/calendar/render?action=TEMPLATE&text={$gText}&dates={$startUtc}/{$endUtc}&details={$gDetails}&location={$gLocation}"
                : '#';
        @endphp

        <div class="add-to-calendar">
            @if($event)
                <button id="addCalBtn" type="button">Add to calendar ▾</button>

                <div id="calMenu" style="display:none; margin-top:.5rem;">
                    <a class="cal-link" href="{{ $googleHref }}" target="_blank" rel="noopener">Google Calendar</a>
                    &nbsp;|&nbsp;
                    <a class="cal-link" href="#" id="downloadIcs">Download .ics (Apple / Outlook / Phone)</a>
                </div>

                <script>
                    (function(){
                        const btn = document.getElementById('addCalBtn');
                        const menu = document.getElementById('calMenu');
                        btn.addEventListener('click', ()=> menu.style.display = menu.style.display === 'none' ? 'block' : 'none');

                        const ev = {!! json_encode([
                            'title' => $evTitle,
                            'start' => $startUtc,
                            'end' => $endUtc,
                            'location' => $evLocation,
                            'description' => $evDesc,
                        ]) !!};

                        document.getElementById('downloadIcs').addEventListener('click', function(e){
                            e.preventDefault();
                            // Build .ics content
                            const now = new Date().toISOString().replace(/[-:]/g,'').split('.')[0] + 'Z';
                            const uid = Date.now() + '@ammamichael';
                            const icsLines = [
                                'BEGIN:VCALENDAR',
                                'VERSION:2.0',
                                'PRODID:-//AR//Wedding//EN',
                                'CALSCALE:GREGORIAN',
                                'BEGIN:VEVENT',
                                'UID:' + uid,
                                'DTSTAMP:' + now,
                                'DTSTART:' + ev.start,
                                'DTEND:' + ev.end,
                                'SUMMARY:' + ev.title,
                                'DESCRIPTION:' + ev.description,
                                'LOCATION:' + ev.location,
                                'END:VEVENT',
                                'END:VCALENDAR'
                            ];
                            const icsContent = icsLines.join('\\r\\n');
                            const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
                            const url = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'ava-mateo-event.ics';
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            URL.revokeObjectURL(url);
                        });
                    })();
                </script>
            @else
                <button disabled type="button">Add to calendar</button>
            @endif
        </div>
      </div>


      <div class="item">
          <h4>CEREMONY — THE PROMISE</h4>
          <h1>{{ optional($countdown->event_at_utc)->format('g A') }}<br><br>
            The Cathedral of the Annunciation of Our Lady<br>
            Surry Hills, NSW</h1>
            @if(!empty($venue?->venue_location))
                <iframe src="{{ $venue->venue_location }}" width="100%" height="450"
                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            @else
                <p>Venue location not set yet.</p>
  @endif
      </div>
      <div class="item">
          <h4>DINNER & PARTY — THE PROOF</h4>
          <h1>6PM<br>
            Machine Hall<br>
            Clarence St, Sydney CBD</h1>
      </div>
      <div class="item">
          <h4>DRESS CODE</h4>
          <h1>Formal Black-Tie Optional</h1>
          <h4>(Dress like you’re entering a vogue dinner, and might end up on a dance floor in Beirut)</h4>
      </div>

      <img src="../../media/The+Garcias+Inline.webp" alt="" class="gracias">
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

    // Dapatkan tombol close
    const closeBtn = document.querySelector('.close-btn');
    // Close menu kalau tombol X diklik
    closeBtn.addEventListener('click', () => {
    panel.classList.remove('open');
    });

  </script>

  <script src="{{ asset('js/hum-menu.js') }}"></script>
</body>
</html>
