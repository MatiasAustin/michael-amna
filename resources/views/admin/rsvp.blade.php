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
                    <p style="color:green; margin-bottom: 10px; background-color:#F3ECDC; padding: 5px 10px; border-radius:5px; box-sizing: border-box; width: fit-content;">{{ session('status') }}</p>
                @endif

                <div style="margin-top: 20px; margin-bottom: 15px; box-sizing: border-box;">
                    <a href="{{ route('admin.rsvp.export') }}"
                    style="padding:8px 20px; background:#F3ECDC; color:#3d1516; text-decoration:none;">
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
                                @php
                                    $rsvpId = $person['rsvp_id'] ?? null;
                                    if (empty($rsvpId) && !empty($person['row_id'])) {
                                        if (preg_match('/rsvp-?([0-9]+)/i', $person['row_id'], $m)) {
                                            $rsvpId = $m[1];
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        {{ $person['source_type'] }}
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        {{ $person['contact_name'] }}
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        <div class="email-edit" style="display:flex; align-items:center; gap:6px;">
                                            <span class="email-display">{{ $person['email'] }}</span>
                                            <input class="email-input"
                                                type="email"
                                                name="rows[{{ $i }}][email]"
                                                value="{{ $person['email'] }}"
                                                style="display:none; width:160px; padding:4px 6px; border:1px solid #ccc; border-radius:3px;">
                                            <button type="button"
                                                onclick="toggleEmailEdit(this)"
                                                aria-label="Edit email"
                                                style="padding:4px; background:#F3ECDC; color:#3d1516; border:1px solid #3d1516; border-radius:3px; display:flex; align-items:center; justify-content:center;">
                                                <svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"></path>
                                                </svg>
                                            </button>
                                        </div>
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
                                            style="width:90px; background: #F3ECDC">
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        <input type="text"
                                            name="rows[{{ $i }}][seat_number]"
                                            value="{{ $person['seat_number'] }}"
                                            style="width:90px; background: #F3ECDC">
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC; text-align:center;">
                                        <div style="display:flex; align-items:center; justify-content:center; gap:6px;">
                                            <span>{{ $person['unique_code'] ?? '-' }}</span>
                                            @if(!empty($person['unique_code']))
                                                <button type="button"
                                                    onclick="copyCode(this.dataset.code)"
                                                    data-code="{{ $person['unique_code'] }}"
                                                    aria-label="Copy code"
                                                    style="padding:4px; background:#F3ECDC; color:#3d1516; border:1px solid #3d1516; border-radius:3px; display:flex; align-items:center; justify-content:center;">
                                                    <svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>

                                    <td style="padding:6px; border:1px solid #F3ECDC;">
                                        @if($person['source_type'] === 'RSVP' && !empty($rsvpId))
                                            <div style="display:flex; flex-wrap:wrap; gap:6px; align-items:center;     justify-content: center;">
                                                {{-- Generate Code (tetap submit langsung) --}}
                                                <button type="submit"
                                                    formaction="{{ route('admin.rsvp.generateCode', ['rsvp' => $rsvpId]) }}"
                                                    formmethod="POST"
                                                    style="padding:4px 8px; font-size:11px; background:#F3ECDC; color:#3d1516; border:none; border-radius:3px;">
                                                    Generate Code
                                                </button>

                                                {{-- Send Email via popup --}}
                                                <button type="button"
                                                    onclick="openSendCodeModal(this)"
                                                    data-rsvp-id="{{ $rsvpId }}"
                                                    data-email="{{ $person['email'] }}"
                                                    data-name="{{ $person['contact_name'] }}"
                                                    data-code="{{ $person['unique_code'] }}"
                                                    style="padding:4px 8px; font-size:11px; background:#3d1516; color:#F3ECDC; border:none; border-radius:3px;">
                                                    Send Email
                                                </button>

                                                {{-- Delete RSVP + guests --}}
                                                <button type="button"
                                                    onclick="confirmDeleteRsvp('{{ $rsvpId }}')"
                                                    aria-label="Delete RSVP and guests"
                                                    style="padding:4px; background:#F3ECDC; color:#3d1516; border:1px solid #3d1516; border-radius:3px; display:flex; align-items:center; justify-content:center;">
                                                    <svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div style="margin-top:20px; color:#f8f3eb;">
                        <h4 style="margin:0 0 10px 0; letter-spacing:0.5px;">NOTES</h4>
                        <p style="margin:0 0 6px 0; font-size: 12px;">
                            Generate Code is only for RSVP (main guests). Additional guests will use the same unique code as their RSVP.
                        <br>
                            Invitation details will show both the RSVP and any additional guests together when extra guests are added in the RSVP form.</p>
                    </div>

                    <button type="submit"
                            style="margin-top:15px; padding:8px 16px; background:#F3ECDC; color:#3d1516; border:none; border-radius:4px;">
                        Save Assignments
                    </button>
                </form>

                @foreach($people as $person)
                    @php
                        $rsvpId = $person['rsvp_id'] ?? null;
                        if (empty($rsvpId) && !empty($person['row_id'])) {
                            if (preg_match('/rsvp-?([0-9]+)/i', $person['row_id'], $m)) {
                                $rsvpId = $m[1];
                            }
                        }
                    @endphp
                    @if($person['source_type'] === 'RSVP' && !empty($rsvpId))
                        <form id="delete-form-{{ $rsvpId }}"
                            action="{{ route('admin.rsvp.destroy', ['rsvp' => $rsvpId]) }}"
                            method="POST"
                            style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                @endforeach

            </div>
        </div>

        {{-- SUCCESS POPUP --}}
        <div id="successModal"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
            <div style="background:#fff; padding:20px; border-radius:8px; width:320px; max-width:90%; color:#3d1516; text-align:center;">
                <h3 style="margin-top:0; margin-bottom:10px;">Success</h3>
                <p id="successMessage" style="margin:0 0 12px 0; font-size:13px; line-height:1.4;"></p>
                <button type="button"
                        onclick="closeSuccessModal()"
                        style="padding:6px 10px; font-size:12px; background:#3d1516; color:#F3ECDC; border:none; border-radius:4px;">
                    Close
                </button>
            </div>
        </div>

        {{-- EMAIL POPUP --}}
        <div id="sendCodeModal"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; ">
            <div style="background:#fff; padding:20px; border-radius:8px; width:420px; max-width:500px; color:#3d1516; box-sizing:border-box;">
                <h3 style="margin-top:0; margin-bottom:10px;">Send RSVP Code</h3>

                <form id="sendCodeForm" method="POST">
                    @csrf
                    <div style="margin-bottom:10px; color:#3d1516">
                        <label for="send-code-email" style="display:block; font-size:13px; margin-bottom:4px;">
                            Email address
                        </label>
                        <input id="send-code-email"
                            name="email"
                            type="email"
                            required
                            style="width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:4px;">
                    </div>

                    <div style="margin-bottom:12px;">
                        <p style="margin:0 0 6px 0; font-size:12px; color:#3d1516; font-weight:bold;">Preview email</p>
                        <div id="emailPreview"
                            style="border:1px solid #eee; background:#F9F6F0; padding:10px; border-radius:6px; font-size:12px; color:#3d1516;">
                            <p style="margin:0 0 8px 0;">Hello <strong id="previewName"></strong>,</p>
                            <p style="margin:0 0 8px 0;">This is your unique code for your invitation / RSVP:</p>
                            <p style="margin:0 0 8px 0; font-size:16px; font-weight:bold;">
                                <span id="previewCode"></span>
                            </p>
                            <p style="margin:0 0 8px 0;">You can use this link to see details:</p>
                            <p style="margin:0 0 8px 0;">
                                <a id="previewLink" href="{{ url('/floor-map') }}#find" target="_blank" style="color:#3d1516; text-decoration:underline;">
                                    Click here to check invitation details
                                </a>
                            </p>
                            <p style="margin:0;">Thank You</p>
                        </div>
                    </div>

                    <div style="display:flex; gap:8px; margin-top:15px;">
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <a id="sendGmailBtn"
                               href="#"
                               target="_blank" rel="noopener"
                               style="padding:6px 10px; font-size:12px; background:#3d1516; color:#F3ECDC; border:none; border-radius:4px; text-decoration:none;">
                                Send by Email
                            </a>
                            <a id="sendOutlookBtn"
                               href="#"
                               target="_blank" rel="noopener"
                               style="padding:6px 10px; font-size:12px; background:#3d1516; color:#F3ECDC; border:none; border-radius:4px; text-decoration:none;">
                                Send by Outlook
                            </a>
                        </div>
                        <div style="display:flex; gap:8px; justify-content:flex-end;">
                            <button type="button"
                                    onclick="closeSendCodeModal()"
                                    style="padding:6px 10px; font-size:12px; background:#eee; border:none; color:#3d1516; border-radius:4px;">
                                Cancel
                            </button>
                            <button type="submit"
                                    style="padding:6px 10px; font-size:12px; background:#3d1516; color:#F3ECDC; border:none; border-radius:4px;">
                                Send with Official Domain
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        {{-- JS EMAIL POPUP --}}
        <script>
        function openSendCodeModal(button) {
            const modal   = document.getElementById('sendCodeModal');
            const form    = document.getElementById('sendCodeForm');
            const emailEl = document.getElementById('send-code-email');
            const name    = button?.dataset?.name || 'Guest';
            const code    = button?.dataset?.code || '';
            const defaultEmail = button?.dataset?.email || '';
            const rsvpId  = button?.dataset?.rsvpId || '';

            // set action form ke route /admin/rsvp/{rsvp}/send-code
            form.action = "{{ url('/admin/rsvp') }}/" + rsvpId + "/send-code";

            // prefill email (kalau ada di DB)
            emailEl.value = defaultEmail || '';

            renderEmailPreview(name, code);

            modal.style.display = 'flex';
        }

        function closeSendCodeModal() {
            const modal = document.getElementById('sendCodeModal');
            modal.style.display = 'none';
        }

        function openSuccessModal(message) {
            const modal = document.getElementById('successModal');
            const messageEl = document.getElementById('successMessage');

            messageEl.textContent = message;
            modal.style.display = 'flex';
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.style.display = 'none';
        }

        function toggleEmailEdit(button) {
            const wrapper = button.closest('.email-edit');
            const display = wrapper.querySelector('.email-display');
            const input   = wrapper.querySelector('.email-input');

            const isEditing = input.style.display !== 'none';

            if (isEditing) {
                input.style.display = 'none';
                display.style.display = 'inline';
                display.textContent = input.value;
            } else {
                input.style.display = 'inline-block';
                display.style.display = 'none';
                input.focus();
            }
        }

        function renderEmailPreview(name, code) {
            const nameEl = document.getElementById('previewName');
            const codeEl = document.getElementById('previewCode');
            const linkEl = document.getElementById('previewLink');
            const baseUrl = "{{ url('/floor-map') }}";
            const emailInput = document.getElementById('send-code-email');
            const gmailBtn = document.getElementById('sendGmailBtn');
            const outlookBtn = document.getElementById('sendOutlookBtn');

            nameEl.textContent = name || 'Guest';
            codeEl.textContent = code || '(will be generated)';

            if (linkEl) {
                const url = code ? `${baseUrl}?code=${encodeURIComponent(code)}#find` : `${baseUrl}#find`;
                linkEl.href = url;
            }

            const email = emailInput?.value || '';
            const inviteLink = code ? `${baseUrl}?code=${encodeURIComponent(code)}#find` : `${baseUrl}#find`;
            const subject = 'Your RSVP Code';
            const body = `Halo ${name || 'Guest'},%0D%0A%0D%0AThis is your invitation code: ${code || '(generate code first)'}%0D%0A%0D%0AOpen details: ${inviteLink}%0D%0A%0D%0AThank You`;

            if (gmailBtn) {
                gmailBtn.href = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}&su=${encodeURIComponent(subject)}&body=${body}`;
            }
            if (outlookBtn) {
                outlookBtn.href = `https://outlook.office.com/mail/deeplink/compose?to=${encodeURIComponent(email)}&subject=${encodeURIComponent(subject)}&body=${body}`;
            }
        }

        // COPY UNIQUE BUTTON
        function copyCode(code) {
            const onFail = () => {
                window.prompt('Copy this code:', code);
                alert('Code copied.');
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(code)
                    .then(() => alert('Code copied.'))
                    .catch(onFail);
            } else {
                onFail();
            }
        }

        function confirmDeleteRsvp(rsvpId) {
            const confirmed = confirm('Are you sure you want to delete this RSVP (main guest) along with the other guests?');
            if (!confirmed) {
                return false;
            }

            const form = document.getElementById('delete-form-' + rsvpId);
            if (form) {
                form.submit();
            }

            return false;
        }
        </script>

        {{-- trigger success popup kalau ada session("success") --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    openSuccessModal(@json(session('success')));
                });
            </script>
        @endif

    </body>
</html>
