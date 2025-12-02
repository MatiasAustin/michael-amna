@extends('admin.layout.structure')
@include('admin.layout.header')
    <style>
        .flash-toast {
            position: fixed;
            top: 18px;
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            padding: 12px 16px;
            border-radius: 10px;
            color: #F3ECDC;
            background: #1a7f37;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            font-size: 14px;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease, transform 0.25s ease;
            min-width: 240px;
            text-align: center;
        }
        .flash-toast.error { background: #b00020; }
        .flash-toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 12000;
        }
        .loading-overlay.show { display: flex; }
        .loading-spinner {
            width: 64px;
            height: 64px;
            border: 4px solid rgba(243,236,220,0.25);
            border-top-color: #F3ECDC;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
    <div class="admin-dashboard-content">
        <h1>Admin Dashboard</h1>
        <p>Amma & Michael</p>

        <div class="admin-dashboard-main">
            @include('admin.layout.sidebar')


            <div class="admin-dashboard-panel">
                <div class="details-section">
                    <h3>Our Day at a Glance</h3>
                    <p style="margin-top:4px;">Manage the timeline blocks and drop in photos that match the sequence.</p>

                    @if(session('success'))
                        <div id="flash-toast" class="flash-toast" data-type="success">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div id="flash-toast" class="flash-toast error" data-type="error">
                            {{ session('error') }}
                        </div>
                    @elseif($errors->any())
                        <div id="flash-toast" class="flash-toast error" data-type="error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div style="padding:8px 12px; background:#d1fae5; color:#065f46; margin-bottom:10px; border-radius:4px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="padding:8px 12px; background:#fee2e2; color:#b91c1c; margin-bottom:10px; border-radius:4px;">
                            <ul style="margin:0; padding-left:18px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div style="display:grid; margin-top: 40px; gap:20px; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                        <div style="border:1px solid #F3ECDC; border-radius:8px; padding:16px;">
                            <h4 style="margin-top:0;">Add entry</h4>
                            <form action="{{ route('admin.dayglance.store') }}" method="POST" enctype="multipart/form-data" style="display:grid; gap:10px;" class="glance-upload-form">
                                @csrf
                                <label>Time Label
                                    <input type="text" name="time_label" placeholder="e.g. 4:45 PM" required>
                                </label>
                                <label>Headline
                                    <input type="text" name="headline" placeholder="Guests Arrive" required>
                                </label>
                                <label>Caption
                                    <input type="text" name="caption" placeholder="Let the celebrations begin">
                                </label>
                                <label>Display Order
                                    <input type="number" name="display_order" min="0" value="0">
                                </label>
                                <label>Photo (optional)
                                    <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
                                    <small style="color:#666;">Up to 20MB; auto-compresses to <5MB</small>
                                </label>
                                <button type="submit" style="padding:10px 14px; background:#3d1516; color:#F3ECDC; border:none; border-radius:4px;">Add</button>
                            </form>
                        </div>

                        <div style="border:1px solid #F3ECDC; border-radius:8px; padding:16px;">
                            <h4 style="margin-top:0;">Existing entries</h4>
                            <div style="display:flex; flex-direction:column; gap:12px;">
                                @forelse($items as $item)
                                    <div style="border:1px solid #F3ECDC30; border-radius:8px; padding:12px;grid-template-columns: 160px 1fr; gap:12px; align-items:start; background: #00000036">
                                        <div style="border:1px dashed #ccc; border-radius:8px; overflow:hidden; min-height:120px; display:flex; align-items:center; justify-content:center; margin-bottom: 20px;">
                                            @if($item->photo_path)
                                                <img src="{{ asset($item->photo_path) }}" alt="Photo {{ $item->id }}" style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                                <span style="color:#999;">Placeholder</span>
                                            @endif
                                        </div>
                                        <div>
                                            <form action="{{ route('admin.dayglance.update', $item) }}" method="POST" enctype="multipart/form-data" style="display:grid; gap:10px;" class="glance-upload-form">
                                                @csrf
                                                @method('PUT')
                                                <label>Time Label
                                                    <input type="text" name="time_label" value="{{ $item->time_label }}" required>
                                                </label>
                                                <label>Headline
                                                    <input type="text" name="headline" value="{{ $item->headline }}" required>
                                                </label>
                                                <label>Caption
                                                    <input type="text" name="caption" value="{{ $item->caption }}">
                                                </label>
                                                <label>Display Order
                                                    <input type="number" name="display_order" min="0" value="{{ $item->display_order }}">
                                                </label>
                                                <label>Replace Photo
                                                    <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
                                                    <small style="color:#666;">Up to 20MB; auto-compresses to <5MB</small>
                                                </label>
                                                <div style="display:flex; gap:10px; align-items:center;">
                                                    <button type="submit" style="padding:8px 12px; background:#3d1516; color:#F3ECDC; border:none; border-radius:4px;">Update</button>
                                                </div>
                                            </form>
                                            <form action="{{ route('admin.dayglance.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this entry?');" style="margin-top:6px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding:8px 12px; background:#b91c1c; color:#fff; border:none; border-radius:4px;">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <p style="margin:0;">No entries yet. Add one to get started.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="loading-overlay" id="uploadOverlay">
        <div class="loading-spinner" aria-label="Uploading"></div>
    </div>

    </body>
    <script>
        const toast = document.getElementById('flash-toast');
        if (toast) {
            const type = toast.dataset.type;
            if (type === 'error') {
                toast.classList.add('error');
            }
            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(() => toast.classList.remove('show'), 4000);
        }

        const overlay = document.getElementById('uploadOverlay');
        document.querySelectorAll('.glance-upload-form').forEach(form => {
            form.addEventListener('submit', () => {
                overlay?.classList.add('show');
            });
        });
    </script>
</html>
