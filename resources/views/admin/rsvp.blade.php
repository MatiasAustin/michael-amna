@extends('admin.layout.structure')
@include('admin.layout.header')
    <div class="admin-dashboard-content" style="padding: 10px 10%;">
        <h1>Admin Dashboard</h1>
        <p>Amma & Michael</p>

        <div class="admin-dashboard-main">
            @include('admin.layout.sidebar')


            <div class="admin-dashboard-panel">
                <h3>RSVP</h3>
                <p>Manage RSVP details here.</p>

                <h1>Seating & Guest List</h1>

                @if(session('status'))
                    <p style="color:green; margin-bottom: 10px;">{{ session('status') }}</p>
                @endif

                <div style="margin-top: 20px; margin-bottom: 15px; box-sizing: border-box;">
                    <a href="{{ route('admin.rsvp.export') }}"
                    style="padding:8px 20px; background:#F3ECDC; color:#7E2625; text-decoration:none;">
                        Export to CSV
                    </a>
                </div>

                <form action="{{ route('admin.rsvp.update') }}" method="POST">
                    @csrf
                    <table style="width:75%; border-collapse:collapse; font-size:14px;">
                        <thead style="background: #471b1a; color: #F3ECDC;">
                            <tr>
                                <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Type</th>
                                <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Name</th>
                                <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Email</th>
                                <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Attend</th>
                                <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Table</th>
                                <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Seat</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($people as $i => $person)
                            <tr>
                                <td style="padding:6px; border:1px solid #F3ECDC;">{{ $person['source_type'] }}</td>
                                <td style="padding:6px; border:1px solid #F3ECDC;">{{ $person['contact_name'] }}</td>
                                <td style="padding:6px; border:1px solid #F3ECDC;">{{ $person['email'] }}</td>
                                <td style="padding:6px; border:1px solid #F3ECDC;">{{ $person['attend'] }}</td>
                                <td style="padding:6px; border:1px solid #F3ECDC;">
                                    <input type="hidden" name="rows[{{ $i }}][id]" value="{{ $person['id'] }}">
                                    <input type="text"
                                        name="rows[{{ $i }}][table_number]"
                                        value="{{ $person['table_number'] }}"
                                        style="width:80px; background: #F3ECDC">
                                </td>
                                <td style="padding:6px; border:1px solid #F3ECDC;">
                                    <input type="text"
                                        name="rows[{{ $i }}][seat_number]"
                                        value="{{ $person['seat_number'] }}"
                                        style="width:80px; background: #F3ECDC">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <button type="submit"
                            style="margin-top:15px; padding:8px 16px; background:#F3ECDC; color:#7E2625; border:none; border-radius:4px;">
                        Save Assignments
                    </button>
                </form>
            </div>
        </div>

    </body>
</html>
