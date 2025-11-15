@extends('admin.layout.structure')
@include('admin.layout.header')
    <div class="admin-dashboard-content" style="padding: 10px 10%;">
        <h1>Admin Dashboard</h1>
        <p>Amma & Michael</p>

        <div class="admin-dashboard-main">
            @include('admin.layout.sidebar')


            <div class="admin-dashboard-panel">

                {{-- Countdown/Schedule --}}
                <h3>Countdown Settings</h3>

                <div class="muted" style="margin-top:8px;font-size:12px;opacity:.8">
                <p>Saved:</p>
                    @if(optional($cd)->event_at_utc)
                        <strong>{{ $cd->headline ?? '—' }}</strong> ·
                        UTC: {{ $cd->event_at_utc->toIso8601String() }} ·
                        Local: {{ $cd->event_at_utc->setTimezone(config('app.timezone'))->toDayDateTimeString() }}
                    @else
                        <em>Not set yet</em>
                    @endif
                </div>
                <br>
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.countdown.update') }}" method="POST">
                    @csrf

                    <label>Headline (optional)</label><br>
                    <input type="text" name="headline" value="{{ old('headline', $cd->headline ?? '') }}">

                    <br>
                    <label>Event Date & Time</label><br>
                    <input type="datetime-local" name="event_at_local"
                    value="{{ old('event_at_local', $datetimeLocal ?? '') }}" required>

                    <br>
                    <label>Timezone</label><br>
                    <select name="tz" required>
                        @php $tz = old('tz', config('app.timezone')); @endphp
                        @foreach(timezone_identifiers_list() as $zone)
                            <option value="{{ $zone }}" @selected($tz===$zone)>{{ $zone }}</option>
                        @endforeach
                    </select>

                    <br>
                    <button type="submit">Save Countdown</button>
                </form>


                <div class="divider" style="height: 0.5px; background-color: #F3ECDC10; margin: 20px 0; width: 100%;"></div>

                {{-- Gallery Management --}}
                <h3>Gallery</h3>
                <p>Manage gallery photos here.</p>

                 <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                    @csrf
                    <input id="photoInput" type="file" name="photo[]" multiple required accept="image/jpeg, image/png, image/webp" style="background: #F3ECDC; color: black;">


                    <p id="errorMsg" style="color:#F3ECDC; font-size:14px; margin-top:5px;"></p>
                    <div id="previewContainer" style="margin-top: 10px;"></div>

                    <button class="upload-photo-admin" type="submit">
                        Upload Photos
                    </button>
                </form>


                <div class="admin-gallery">
                    @forelse ($photos as $photo)
                        <div class="pictures-card">
                            <img src="{{ asset('/' . $photo->filename) }}" alt="Gallery Image" width="150px">

                            <div class="picture-actions">
                                {{-- Download button: prefer a named route if available, otherwise direct asset with download attribute --}}
                                @if(\Illuminate\Support\Facades\Route::has('gallery.download'))
                                    <a href="{{ route('gallery.download', $photo->id) }}" class="download-button" target="_blank" rel="noopener">Download</a>
                                @else
                                    <a href="{{ asset('/' . $photo->filename) }}" class="download-button" download target="_blank" rel="noopener">Download</a>
                                @endif

                                <form action="{{ route('gallery.destroy', $photo->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-button" type="submit">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">No photo yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <script>
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('previewContainer');
        const errorMsg = document.getElementById('errorMsg');
        const maxSize = 20 * 1024 * 1024; // 20MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const maxFiles = 3;

        input.addEventListener('change', function () {
            preview.innerHTML = '';
            errorMsg.textContent = '';

            const files = Array.from(this.files);

            if (files.length > maxFiles) {
                errorMsg.textContent = `You can only upload up to ${maxFiles} photos.`;
                return input.value = '';
            }

            files.forEach(file => {
                if (file.size > maxSize) {
                    errorMsg.textContent = 'One of the files is too large. Maximum allowed size is 20MB per photo.';
                    return input.value = '';
                }

                if (!allowedTypes.includes(file.type)) {
                    errorMsg.textContent = 'Unsupported file format. Only JPG, PNG, and WEBP are allowed.';
                    return input.value = '';
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '140px';
                    img.style.margin = '10px';
                    img.style.borderRadius = '10px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>

    </body>
</html>
