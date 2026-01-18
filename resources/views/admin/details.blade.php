@extends('admin.layout.structure')
@include('admin.layout.header')
    <div class="admin-dashboard-content">
        <h1>Admin Dashboard</h1>
        <p>Michael & Amna</p>

        <div class="admin-dashboard-main">
            @include('admin.layout.sidebar')

            <div class="admin-dashboard-panel">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->has('floor_map'))
                    <div class="alert alert-error">
                        {{ $errors->first('floor_map') }}
                    </div>
                @endif
                
                <div class="details-section">
                    <div class="maps card">
                        <h3>Venue Location</h3>

                        <form action="{{ route('admin.details.update') }}" method="POST" class="details-form">
                            @csrf
                            <label for="venue_location" style="display:block; margin-bottom:5px;">Google Maps Embed Link:</label>
                            <input type="text" id="venue_location" name="venue_location"
                                    class="form-control"
                                    placeholder="Enter Google Maps embed link"
                                    value="{{ old('venue_location', $venue->venue_location ?? '') }}" required>
                            <div style="margin-top: 10px;">
                                <button type="submit" class="btn btn-primary">Update Location</button>
                            </div>
                        </form>
                    </div>

                    <div class="floor-map card">
                        <h3>Floor Map</h3>

                        {{-- preview current floor map --}}
                        @if(!empty($floorMapUrl))
                            <p style="margin-bottom:10px;">Current floor map:</p>
                            <img src="{{ $floorMapUrl }}" alt="Floor Map"
                                style="max-width:100%; height:auto; border:1px solid var(--border-color); border-radius:4px; margin-bottom:15px; display:block;">
                        @else
                            <p style="margin-bottom:10px;"><em>Belum ada floor map.</em></p>
                        @endif

                        <form action="{{ url('/admin/details/floor-map') }}"
                            method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <label for="floor_map" style="display:block; margin-bottom:5px;">Upload Floor Map (PDF/JPG):</label>
                            <input type="file" name="floor_map" id="floor_map" accept=".jpg,.jpeg" required style="margin-bottom: 10px;">
                            
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    Update Floor Map
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin.layout.footer')
</body>
</html>
