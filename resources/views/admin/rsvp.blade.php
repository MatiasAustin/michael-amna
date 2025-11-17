@extends('admin.layout.structure')
@include('admin.layout.header')
    <div class="admin-dashboard-content">
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

                <form action="{{ route('admin.rsvp.update') }}" method="POST" >
                    @csrf
                    <div style="width:100%; overflow-x:auto; -webkit-overflow-scrolling: touch; margin-top:10px;">
                        <table style="width:100%; border-collapse:collapse; font-size:12px; overflow-x: auto;">
                            <thead style="background: #471b1a; color: #F3ECDC;">
                                <tr>
                                    <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Type</th>
                                    <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Name</th>
                                    <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Email</th>
                                    <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Attend</th>
                                    <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Table</th>
                                    <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Seat</th>
                                    <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Code</th>
                                    <th style="padding:5px; border:0.5px solid #F3ECDC; font-weight: normal;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($people as $i => $person)
                                <tr>
                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        {{ $person['source_type'] }}
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        {{ $person['contact_name'] }}
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        {{ $person['email'] }}
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        {{ $person['attend'] }}
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        {{-- penting: row_id buat update() --}}
                                        <input type="hidden"
                                            name="rows[{{ $i }}][row_id]"
                                            value="{{ $person['row_id'] }}">

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

                                    <td style="padding:6px; border:1px solid #F3ECDC; text-align:center;">
                                        {{ $person['unique_code'] ?? '-' }}
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        @if($person['source_type'] === 'RSVP')
                                            {{-- Generate Code (tetap submit langsung) --}}
                                            <button type="submit"
                                                formaction="{{ route('admin.rsvp.generateCode', ['rsvp' => $person['rsvp_id']]) }}"
                                                formmethod="POST"
                                                style="padding:4px 8px; font-size:11px; background:#F3ECDC; color:#7E2625; border:none; border-radius:3px; margin-bottom:4px;">
                                                Generate Code
                                            </button>

                                            {{-- Send Email via popup --}}
                                            <button type="button"
                                                onclick="openSendCodeModal('{{ $person['rsvp_id'] }}', '{{ $person['email'] }}')"
                                                style="padding:4px 8px; font-size:11px; background:#7E2625; color:#F3ECDC; border:none; border-radius:3px;">
                                                Send Email
                                            </button>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <button type="submit"
                            style="margin-top:15px; padding:8px 16px; background:#F3ECDC; color:#7E2625; border:none; border-radius:4px;">
                        Save Assignments
                    </button>
                </form>

            </div>
        </div>

        {{-- EMAIL POPUP --}}
        <div id="sendCodeModal"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; ">
            <div style="background:#fff; padding:20px; border-radius:8px; width:320px; max-width:90%; color:#7E2625;">
                <h3 style="margin-top:0; margin-bottom:10px;">Send RSVP Code</h3>

                <form id="sendCodeForm" method="POST">
                    @csrf
                    <div style="margin-bottom:10px; color:#7E2625">
                        <label for="send-code-email" style="display:block; font-size:13px; margin-bottom:4px;">
                            Email address
                        </label>
                        <input id="send-code-email"
                            name="email"
                            type="email"
                            required
                            style="width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:4px;">
                    </div>

                    <div style="text-align:right; margin-top:15px;">
                        <button type="button"
                                onclick="closeSendCodeModal()"
                                style="padding:6px 10px; font-size:12px; margin-right:6px; background:#eee; border:none; color:#7E2625; border-radius:4px;">
                            Cancel
                        </button>
                        <button type="submit"
                                style="padding:6px 10px; font-size:12px; background:#7E2625; color:#F3ECDC; border:none; border-radius:4px;">
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- JS EMAIL POPUP --}}
        <script>
        function openSendCodeModal(rsvpId, defaultEmail) {
            const modal   = document.getElementById('sendCodeModal');
            const form    = document.getElementById('sendCodeForm');
            const emailEl = document.getElementById('send-code-email');

            // set action form ke route /admin/rsvp/{rsvp}/send-code
            form.action = "{{ url('/admin/rsvp') }}/" + rsvpId + "/send-code";

            // prefill email (kalau ada di DB)
            emailEl.value = defaultEmail || '';

            modal.style.display = 'flex';
        }

        function closeSendCodeModal() {
            const modal = document.getElementById('sendCodeModal');
            modal.style.display = 'none';
        }
    </script>



    </body>
</html>
