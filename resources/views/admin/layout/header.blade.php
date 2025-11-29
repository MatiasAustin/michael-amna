<div class="admin-header"
    style="display: flex; justify-content: space-between; align-items: center;">
    <div class="admin-brand">
        <a href="{{ url('/') }}">
            <img src="{{ asset('media/MA-favicon-beige.png') }}" alt="Admin Logo" width="40" height="auto">
        </a>
    </div>
    <div class="admin-actions">
        <form action="{{ route('logout') }}" method="POST">@csrf<button class="logout-button">Logout</button></form>
    </div>
</div>
