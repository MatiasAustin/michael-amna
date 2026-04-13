<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Michael & Amna — RSVP</title>
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
    .video-wrapper {
        margin-top: 60px;
        width: 100%;
        max-width: 900px;
        border-radius: 16px;
        overflow: hidden;
        background: #000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.24);
        position: relative;
    }
    .video-wrapper video {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 16px;
    }
    .video-controls {
        position: absolute;
        right: 12px;
        bottom: 12px;
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .video-controls button {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #F3ECDC;
        cursor: pointer;
        display: grid;
        place-items: center;
        font-size: 14px;
        transition: transform 0.15s ease, color 0.15s ease;
    }
    .video-controls button:hover {
        transform: scale(1.08);
        color: #ffffff;
    }
    /* Floating Help Button */
    .floating-help {
        position: fixed;
        bottom: 30px;
        right: 30px;
        height: 50px;
        /* Outlined Style */
        background-color: transparent;
        border: 1px solid #F3ECDC;
        color: #F3ECDC;
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        z-index: 9998;
        transition: width 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        text-decoration: none;
        overflow: hidden;
        /* Initial state: circle - account for border */
        width: 50px;
        box-sizing: border-box;
    }
    .floating-help:hover {
        /* Expanded state */
        width: 200px;
        background-color: #F3ECDC; /* Fill on hover for contrast */
        color: #3d1516;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
    }
    
    .floating-text {
        opacity: 0;
        white-space: nowrap;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        max-width: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        transform: translateX(-10px);
    }

    .floating-help:hover .floating-text {
        opacity: 1;
        max-width: 150px; /* Space for text */
        margin-left: 15px;
        margin-right: 5px;
        transform: translateX(0);
    }

    .floating-icon {
        width: 48px; /* 50px - 2px border */
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .floating-help svg {
        width: 24px;
        height: 24px;
        fill: currentColor;
    }
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
      <button class="hamb" aria-label="Open menu" aria-controls="mPanel" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>
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
        <h2 style="margin: 20px 0; letter-spacing: 5px;">WE CAN’T WAIT TO CELEBRATE WITH YOU</h2>
        <p>
            Please let us know if you’ll be joining us for the magic.<br>
            Kindly respond by 14 of March 2026.
        </p>
    </div>

    {{-- RSVP FORM --}}
    <form action="{{ route('rsvp.store') }}" method="POST" id="rsvpForm">
        @csrf
        {{-- tamu utama / contact --}}
        <input name="full_name"  placeholder="Your Full Name" required>
        <input name="email"      placeholder="Email (Required)" type="email" required>
        <input name="dietary"    placeholder="Dietary Requirements (optional)">

        <fieldset class="attendance">
            <legend style="margin-bottom: 20px;">Are you attending?</legend>
            <label class="radio">
                <input class="radio" type="radio" name="attend" value="yes" required>
                Accept with pleasure
            </label>
            <label class="radio">
                <input class="radio" type="radio" name="attend" value="no" required>
                Decline with regret
            </label>
        </fieldset>

        <h5 style="margin-top: 40px;">Additional Guests</h5>
        <div id="guestList" style="display: flex; flex-direction: column; gap: 20px;">
            {{-- row template pertama --}}
            <div class="guest-row" data-index="0">
                <input name="guests[0][full_name]" placeholder="Guest Full Name" required>
                <input name="guests[0][email]"     placeholder="Guest Email (optional)" type="email">
                <input name="guests[0][dietary]"   placeholder="Dietary Requirements (optional)">
                <button type="button" class="remove">Remove</button>
            </div>
        </div>
        <button type="button" id="addGuest">+ Add another guest</button>

        <h5 style="margin-top: 40px;">Message for the couple (optional)</h5>
        <textarea name="message" rows="3" placeholder="Write a short note…"></textarea>

        <button type="submit">I DO</button>
    </form>
    
    @if(!empty($help_email))
        <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ trim($help_email) }}&su=Report%20Problem%20-%20ForeverKhoury"
           class="floating-help"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Report a problem">
            
            <span class="floating-text">Report Problem</span>
            
            <div class="floating-icon">
                {{-- Rounded Warning Icon --}}
                <svg viewBox="0 0 24 24">
                    <path d="M12 2C12.4 2 12.8 2.2 13 2.5L22.5 19.1C22.9 19.8 22.4 20.8 21.5 20.8H2.5C1.6 20.8 1.1 19.8 1.5 19.1L11 2.5C11.2 2.2 11.6 2 12 2ZM12 15C11.4 15 11 15.4 11 16V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V16C13 15.4 12.6 15 12 15ZM11 7V13C11 13.6 11.4 14 12 14C12.6 14 13 13.6 13 13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7Z"/>
                </svg>
            </div>
        </a>
    @endif

    {{-- JS untuk dynamic guest rows (pastikan file ini cuma ngurus tambah/hapus guest, bukan submit) --}}
    <script src="{{ asset('js/rsvp-form.js') }}?v={{ time() }}"></script>

    {{-- POPUP --}}
    <div id="successPopup" class="popup" style="
        display:none;
        position:fixed;
        inset:0;
        z-index:9999;
        justify-content:center;
        background-color: #0000009c;
        align-items:center;
        margin:0 auto;
    ">
        <div style="background:#F3ECDC; padding:20px 26px;max-width:320px; text-align:center;
        border-radius: 20px;
        border 1px solid #3d1516;">
            <p style="margin:0 0 12px; color:#3d1516;">Thank you! Your RSVP has been recorded.</p>
            <button id="closePopupBtn" style="padding:5px 30px;border:0;background:#3d1516;color:#F3ECDC;cursor:pointer">Close</button>
        </div>
    </div>

    <script>
        // popup basic
        const popupEl = document.getElementById('successPopup');
        function openPopup(){ popupEl.style.display = 'flex'; }
        function closePopup(){ popupEl.style.display = 'none'; }
        document.getElementById('closePopupBtn')?.addEventListener('click', closePopup);
    </script>

    {{-- kalau session success ada, buka popup setelah redirect --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const popup = document.getElementById('successPopup');
                if (popup) popup.style.display = 'flex';
            });
        </script>
    @endif

    <div class="video-wrapper" id="rsvpVideoWrapper">
        <video id="rsvpVideo" autoplay muted loop playsinline preload="auto">
            <source src="{{ asset('media/michael-amna-video.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="video-controls">
            <button type="button" id="playPauseBtn" aria-label="Play or Pause">❚❚</button>
            <button type="button" id="muteBtn" aria-label="Mute or Unmute">🔇</button>
            <button type="button" id="fullscreenBtn" aria-label="Fullscreen">⛶</button>
        </div>
    </div>
  </section>

  <footer>All Right Reserved by @Freelab2025</footer>

  <script>
    /// General
    const hamb  = document.querySelector('.hamb');
    const panel = document.getElementById('mPanel');

    function toggle(){
      const open = panel.classList.toggle('open');
      hamb.setAttribute('aria-expanded', open);
      document.body.style.overflow = open ? 'hidden' : '';
    }

    hamb.addEventListener('click', toggle);
    panel.querySelectorAll('a').forEach(a => a.addEventListener('click', toggle));
    window.addEventListener('keydown', e => {
        if (e.key === 'Escape' && panel.classList.contains('open')) toggle();
    });

    // tombol close (X) di panel
    const closeBtn2 = document.querySelector('.close-btn');
    closeBtn2.addEventListener('click', () => {
        panel.classList.remove('open');
        hamb.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    });

    // Video controls (play/pause/fullscreen)
    const videoEl = document.getElementById('rsvpVideo');
    const wrapperEl = document.getElementById('rsvpVideoWrapper');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const muteBtn = document.getElementById('muteBtn');
    const fullscreenBtn = document.getElementById('fullscreenBtn');

    function updatePlayLabel() {
        if (!playPauseBtn) return;
        playPauseBtn.textContent = videoEl.paused ? '▶' : '❚❚';
    }

    function updateMuteLabel() {
        if (!muteBtn) return;
        muteBtn.textContent = videoEl.muted ? '🔇' : '🔊';
    }

    function togglePlayPause() {
        if (videoEl.paused) {
            videoEl.play();
        } else {
            videoEl.pause();
        }
    }

    function toggleMute() {
        videoEl.muted = !videoEl.muted;
        updateMuteLabel();
    }

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            (wrapperEl.requestFullscreen || wrapperEl.webkitRequestFullscreen || wrapperEl.msRequestFullscreen)?.call(wrapperEl);
            // Attempt to lock to landscape on supported mobile browsers
            if (screen.orientation?.lock) {
                screen.orientation.lock('landscape').catch(() => {});
            }
        } else {
            (document.exitFullscreen || document.webkitExitFullscreen || document.msExitFullscreen)?.call(document);
            if (screen.orientation?.unlock) {
                screen.orientation.unlock();
            }
        }
    }

    playPauseBtn?.addEventListener('click', togglePlayPause);
    muteBtn?.addEventListener('click', toggleMute);
    fullscreenBtn?.addEventListener('click', toggleFullscreen);
    videoEl.addEventListener('play', updatePlayLabel);
    videoEl.addEventListener('pause', updatePlayLabel);
    videoEl.addEventListener('volumechange', updateMuteLabel);
    document.addEventListener('fullscreenchange', () => {
        fullscreenBtn.textContent = document.fullscreenElement ? '↙↗' : '⛶';
    });
    updatePlayLabel();
    updateMuteLabel();
  </script>

  <script src="{{ asset('js/custom.js') }}"></script>
  <script src="{{ asset('js/hum-menu.js') }}"></script>
</body>
</html>
