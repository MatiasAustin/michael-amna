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
                    <input type="file" name="photo[]" multiple required><br>
                    <button type="submit">Upload</button>
                </form>

                <div class="admin-gallery">
                    @forelse ($photos as $photo)
                        <div class="pictures-card">
                            <img src="{{ asset('storage/' . $photo->filename) }}" alt="Gallery Image" width="150px">
                            <form action="{{ route('gallery.destroy', $photo->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="delete-button" type="submit">Delete</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-center text-muted">No photo yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    </body>
</html>
