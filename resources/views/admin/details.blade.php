@extends('admin.layout.structure')
@include('admin.layout.header')
    <div class="admin-dashboard-content">
        <h1>Admin Dashboard</h1>
        <p>Amma & Michael</p>

        <div class="admin-dashboard-main">
            @include('admin.layout.sidebar')


            <div class="admin-dashboard-panel">
                <div class="details-section">
                        <div class="maps">
                            <h3>Venue Location</h3>

                             @if(session('success'))
                                <div style="padding:8px 12px; background:#d1fae5; color:#065f46; margin-bottom:10px; border-radius:4px;">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->has('floor_map'))
                                <div style="padding:8px 12px; background:#fee2e2; color:#b91c1c; margin-bottom:10px; border-radius:4px;">
                                    {{ $errors->first('floor_map') }}
                                </div>
                            @endif
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
                            {{-- <div class="map-preview" style="margin-top:12px;">
                            <iframe id="mapPreview" src="{{ $venue->venue_location ?? '' }}" width="100%" height="500"
                                    style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div> --}}
                        </div>


                        <div class="floor-map" style="margin-top:40px; border-top:1px solid #ccc; padding-top:20px;">
                        <h3>Floor Map</h3>



                        {{-- preview current floor map --}}
                        @if(!empty($floorMapUrl))
                            <p>Current floor map:</p>
                            <img src="{{ $floorMapUrl }}" alt="Floor Map"
                                style="max-width:400px; border:1px solid #ddd; margin-bottom:10px;">
                        @else
                            <p><em>Belum ada floor map.</em></p>
                        @endif

                        <form action="{{ url('/admin/details/floor-map') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            style="margin-top:10px;">
                            @csrf
                            <label for="floor_map">Upload Floor Map (JPG):</label><br>
                            <input type="file" name="floor_map" id="floor_map" accept=".jpg,.jpeg" required>

                            <br><br>

                            <button type="submit"
                                    style="padding:8px 16px; background:#3d1516; color:#F3ECDC; border:none; border-radius:4px; font-size: 14px; text-transform: uppercase;">
                                Update Floor Map
                            </button>
                        </form>
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
