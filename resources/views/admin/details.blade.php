@extends('admin.layout.structure')
@include('admin.layout.header')
    <div class="admin-dashboard-content" style="padding: 10px 10%;">
        <h1>Admin Dashboard</h1>
        <p>Amma & Michael</p>

        <div class="admin-dashboard-main">
            @include('admin.layout.sidebar')


            <div class="admin-dashboard-panel">
                <div class="details-section">
                        <div class="maps">
                            <h3>Venue Location</h3>

                            @if(session('success'))
                            <p class="text-success">{{ session('success') }}</p>
                            @endif

                            <form action="{{ route('admin.details.update') }}" method="POST" class="details-form">
                            @csrf
                            <label for="venue_location">Google Maps Embed Link:</label>
                            <input type="text" id="venue_location" name="venue_location"
                                    placeholder="Enter Google Maps embed link"
                                    value="{{ old('venue_location', $venue->venue_location ?? '') }}" required>
                            <button type="submit">Update Location</button>
                            </form>

                            {{-- Preview (opsional) --}}
                            <div class="map-preview" style="margin-top:12px;">
                            <iframe id="mapPreview" src="{{ $venue->venue_location ?? '' }}" width="100%" height="360"
                                    style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>

                    <script>
                    const input = document.getElementById('venue_location');
                    const frame = document.getElementById('mapPreview');
                    input.addEventListener('input', () => {
                        const v = input.value.trim();
                        frame.src = v || '';
                    });
                    </script>

            </div>
        </div>

    </body>
</html>
