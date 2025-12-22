<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Michael & Amna — Details</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  <link rel="stylesheet" href="https://use.typekit.net/zlr0kjr.css">
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
        margin-top: 12px;
        padding: 12px 20px;
        border-bottom: 1px solid #F3ECDC;
        color: #F3ECDC;
        background: transparent;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
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
        <h2 style="margin: 20px 0; letter-spacing: 10px;">Saturday</h2>
        <p>{{ optional($countdown->event_at_utc)->format('d M Y') }}</p>
        @php
            $event = $countdown?->event_at_utc;
            // fallback values
            $evTitle = 'Michael & Amna';
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
                    {{-- &nbsp;|&nbsp;
                    <a class="cal-link" href="#" id="downloadIcs">Download .ics (Apple / Outlook / Phone)</a> --}}
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
                            const uid = Date.now() + '@michaelamna';
                            const icsLines = [
                                'BEGIN:VCALENDAR',
                                'VERSION:2.0',
                                'PRODID:-//MA//Wedding//EN',
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
                            a.download = 'michael-amna-event.ics';
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

        {{-- <div class="divider" style="height: 0.5px; background-color: #F3ECDC10; margin: 20px 0; width: 100%;"></div> --}}

      <div class="item" style="margin-top:40px; background-image: url({{ asset('media/MA-favicon-trans.png') }}); background-size: contain; background-repeat: no-repeat; background-position: center; background-opacity: 0.1;">
        <h2 style="margin: 20px 0; letter-spacing: 10px;">Our Ceremony</h2>

        <p>Will  begin at {{ optional($countdown->event_at_utc)->format('g A') }}</p>

        <h4 style="margin: 0; font-weight: 400;"><b>Doltone House - Jones Bay Wharf</b></h4>
        <p><i>Level 3, 26-32 Pirrama Road, Pyrmont NSW 2009</i></p>
            @if(!empty($venue?->venue_location))
                <a href="{{ $venue->venue_location }}" target="_blank" rel="noopener" class="map-btn">
                    Open in Google Maps
                </a>
            @else
                <p>Venue location not set yet.</p>
            @endif
      </div>

      <div class="item" style="padding: 20px 10% 0px 10%; border-radius: 20px;">
          <p style="margin:0;">After we say “I DO” we will celebrate with drinks and canapés during cocktail hour,<br>before moving inside for dinner.</p>
      </div>

      <div class="item" style="margin-top:40px;">
          <h2 style="margin: 20px 0; letter-spacing: 10px;">DRESS CODE</h2>
          <p>Black-Tie</p>
      </div>


    <div class="divider" style="height: 0.5px; background-color: #F3ECDC10; margin: 20px 0; width: 100%;"></div>

    <div class="faq" style="width: 100%; margin-left: auto; margin-right: auto; padding: 0 16px;">
        <h2 style="margin: 20px 0; letter-spacing: 10px;">FAQ</h2>

        @php
            $faqs = [
                ['q' => 'WHAT TIME SHOULD WE ARRIVE?', 'a' => 'Please aim to arrive 10–15 minutes before the ceremony to secure one of the limited 60 seats available.
                <br><br>
                If you arrive closer to the start time, that’s completely fine — standing room will be available.'],
                ['q' => 'IS THERE A DRESS CODE?', 'a' => 'We encourage you to dress to impress and enjoy the elegance of the night — it will also make for beautiful photos!'],
                ['q' => 'CAN WE BRING OUR CHILDREN?', 'a' => 'While we adore your little ones, this will be an adults-only celebration.
                <br><br>
                We hope you enjoy a night off to relax and celebrate with us.'],
                ['q' => 'IS THERE PARKING AT THE VENUE?', 'a' => 'Yes. Parking is available at Wilson Jones Bay Wharf Car Park, located at 17–23 Pirrama Road, Pyrmont (opposite Doltone House).
                <br><br>
                Guests can pre-book parking via <a href="https://bookabay.wilsonparking.com.au" target="_blank" rel="noopener">bookabay.wilsonparking.com.au</a> using the promotional code Doltone.
                <br><br>
                We do still recommend Uber or rideshare if you’d prefer a stress-free night, but parking is readily available for those driving.'],
                ['q' => 'WILL FOOD CATER TO DIETARY REQUIREMENTS', 'a' => 'Absolutely.
                <br><br>
                Please let us know your dietary requirements when completing your RSVP, and Doltone House will do their best to accommodate.'],
                ['q' => 'WHAT HAPPENS AFTER THE CEREMONY', 'a' => 'Following the ceremony, guests are invited to remain on the wharf for drinks and canapés, mingling while we prepare for the reception to open.'],
                ['q' => 'WHERE SHOULD WE STAY IF WE ARE TRAVELLING?', 'a' => 'We are currently organising discounted accommodation options recommended by Doltone House.<br>Please reach out to us if you’d like these details.
                <br>
                <ul>
                    <li><p>Closest & Most Convenient (Pyrmont / Darling Harbour)</p></li>
                    <li><p>The Darling – Luxury boutique hotel at The Star precinct</p></li>
                    <li><p>The Star Grand Hotel – Stylish and comfortable</p></li>
                    <li><p>Hotel Woolstore 1888 – Charming boutique option</p></li>
                    <li><p>Novotel Sydney on Darling Harbour – Reliable choice with harbour views</p></li>
                    <li><p>Aiden Hotel Darling Harbour – Modern and well-reviewed</p></li>
                    <li><p>Ibis Sydney Darling Harbour – Good value and location</p></li>
                    <li><p>Terminus Hotel Pyrmont – Cozy, boutique style with pub downstairs</p></li>
                </ul>'],
                ['q' => 'IS THERE HASHTAGS TO SHARE PHOTOS?', 'a' => 'Not just yet — most likely #ForeverKhoury.
                <br><br>
                We’ll confirm on the night. In the meantime, you’re welcome to leave messages on the website, and on the day you’ll also be able to upload photos directly.'],
                ['q' => 'HOW DOES THE FLOOR MAP WORK?', 'a' => 'A few weeks out from the date, you will recieve a code via email which you will be enter into “enter unique code” search and it will allocate you your table number for the night.'],
            ];
        @endphp

        <div class="faq-list" style="margin-top: 20px; width: 100%; margin-left: auto; margin-right: auto;">
            @foreach($faqs as $i => $item)
                <div class="faq-item">
                    <button class="faq-toggle" type="button" aria-expanded="false" aria-controls="faq-{{ $i }}" style="width:100%; text-align:left; padding:12px; font-size:14px; border:none; cursor:pointer; display:flex; justify-content:space-between; align-items:center; margin-top: 10px; text-transform: uppercase;">
                        {{ $item['q'] }} <span class="chev">▾</span>
                    </button>
                    <div id="faq-{{ $i }}" class="faq-content" hidden style="padding:12px; border-left:4px solid #2b0f10; background:#F3ECDC20; margin-top:4px; text-align:left;">
                        <p>{!! $item['a'] !!}</p>
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


    </section>

  <footer>All Right Reserved by @Freelab2025</footer>

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
