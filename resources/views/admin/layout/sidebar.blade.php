<div class="admin-nav-wrapper">
    <button class="admin-nav-toggle" id="navToggle">Menu</button>
    <ul class="admin-nav" id="adminNav">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.details') }}">Finer Details</a></li>
        <li><a href="{{ route('admin.dayglance.index') }}">Our Day at a Glance</a></li>
        <li><a href="{{ route('admin.rsvp') }}">RSVP</a></li>
    </ul>
</div>

<script>
    document.getElementById('navToggle').addEventListener('click', function() {
        document.getElementById('adminNav').classList.toggle('active');
    });
</script>
