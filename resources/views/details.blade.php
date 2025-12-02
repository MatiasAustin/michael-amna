<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ava & Mateo — Details</title>
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

      <div class="item">
          <h4>Saturday</h4>
        <h1>{{ optional($countdown->event_at_utc)->format('d M Y') }}</h1>
        @php
            $event = $countdown?->event_at_utc;
            // fallback values
            $evTitle = 'Michael & Amna — The Promise';
            $evLocation = $venue?->venue_name ? $venue->venue_name . ', ' . ($venue?->venue_location_text ?? '') : 'Doltone House - Jones Bay Wharf - Level 3, 26-32 Pirrama Road, Pyrmont NSW 2009';
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

        <div class="divider" style="height: 0.5px; background-color: #F3ECDC10; margin: 20px 0; width: 100%;"></div>

      <div class="item" style="margin-top:40px;">
          <h4>CEREMONY — THE PROMISE</h4>
          <p>Our ceremony will begin at {{ optional($countdown->event_at_utc)->format('g A') }}<br><br>
            Doltone House - Jones Bay Wharf - Level 3, 26-32 Pirrama Road, Pyrmont NSW 2009</p>
            @if(!empty($venue?->venue_location))
                <iframe src="{{ $venue->venue_location }}" width="100%" style="min-height:500px; border:0; border-radius: 20px; margin: 0 auto" allowfullscreen="" loading="lazy"></iframe>
            @else
                <p>Venue location not set yet.</p>
  @endif
      </div>

      <div class="item">
          <h4>DRESS CODE</h4>
          <p>Black-Tie</p>
      </div>
      <div class="item">
          <p>After we say “I DO” we will celebrate with drinks and canapés during cocktail hour, before moving inside for dinner.</p>
      </div>


    <div class="divider" style="height: 0.5px; background-color: #F3ECDC10; margin: 20px 0; width: 100%;"></div>

    <div class="faq" style="width: 100%; margin-left: auto; margin-right: auto; padding: 0 16px;">
        <h4>FAQ</h4>

        @php
            $faqs = [
                ['q' => 'Do I need to RSVP?', 'a' => 'Please RSVP via the RSVP page. Kindly respond by the date on your invitation.'],
                ['q' => 'Is parking available?', 'a' => 'Limited parking is available nearby. Rideshare or public transport is recommended.'],
                ['q' => 'Can I bring a guest?', 'a' => 'Only guests listed on your invitation can be accommodated. Please contact us for changes.'],
                ['q' => 'Is the venue accessible?', 'a' => 'The venue is wheelchair accessible. Contact us for additional assistance.'],
                ['q' => 'What should I wear?', 'a' => 'Formalwear.'],
                ['q' => 'Can I bring my children?', 'a' => 'No children under 18.'],
                ['q' => 'When should I RSVP by?', 'a' => 'Confirm with Auzita.'],
                ['q' => 'Will transportation be provided?', 'a' => 'No.'],
                ['q' => 'Where should I park?', 'a' => 'Wilson Parking will provide free parking vouchers for guests.'],
                ['q' => 'What time should I arrive?', 'a' => '4:30pm to enjoy the views and get ready for the ceremony.'],
            ];
        @endphp

        <div class="faq-list" style="margin-top: 20px; width: 100%; margin-left: auto; margin-right: auto;">
            @foreach($faqs as $i => $item)
                <div class="faq-item">
                    <button class="faq-toggle" type="button" aria-expanded="false" aria-controls="faq-{{ $i }}" style="width:100%; text-align:left; padding:12px; font-size:16px; border:none; cursor:pointer; display:flex; justify-content:space-between; align-items:center; margin-top: 10px;">
                        {{ $item['q'] }} <span class="chev">▾</span>
                    </button>
                    <div id="faq-{{ $i }}" class="faq-content" hidden style="padding:12px; border-left:4px solid #2b0f10; background:#F3ECDC20; margin-top:4px; text-align:left;">
                        <p>{{ $item['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <script>
            (function(){
                document.querySelectorAll('.faq-toggle').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.getAttribute('aria-controls');
                        const panel = document.getElementById(id);
                        const isOpen = btn.getAttribute('aria-expanded') === 'true';
                        btn.setAttribute('aria-expanded', String(!isOpen));
                        panel.hidden = isOpen;
                    });
                });
            })();
        </script>
    </div>

    <div class="divider" style="height: 0.5px; background-color: #F3ECDC10; margin: 20px 0; width: 100%;"></div>

      <img src="../../media/ForeverKhoury.png" alt="" class="gracias">
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
