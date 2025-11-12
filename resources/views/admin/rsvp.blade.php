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

            </div>
        </div>

    </body>
</html>
