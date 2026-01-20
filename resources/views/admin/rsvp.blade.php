@extends('admin.layout.structure')
@include('admin.layout.header')

<div class="admin-dashboard-content">
    <h1>Admin Dashboard</h1>
    <p>Michael & Amna</p>

    <div class="admin-dashboard-main">
        @include('admin.layout.sidebar')

        <div class="admin-dashboard-panel">
            <h3>RSVP</h3>
            <p>Manage RSVP details here.</p>

            <div class="mb-4">
                <h1>Seating & Guest List</h1>
            </div>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="mb-4 my-4">
                <a href="{{ route('admin.rsvp.export') }}" class="btn btn-primary">
                    Export to CSV
                </a>
            </div>

            <form action="{{ route('admin.rsvp.update') }}" method="POST" >
                @csrf
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Dietary</th>
                                <th>Attend</th>
                                <th>Table</th>
                                <th>Seat</th>
                                <th>Code</th>
                                <th>Actions</th>
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
                                <td>{{ $person['source_type'] }}</td>
                                <td>{{ $person['contact_name'] }}</td>
                                <td>
                                    <div class="email-edit" style="display:flex; align-items:center; gap:6px;">
                                        <span class="email-display">{{ $person['email'] }}</span>
                                        <input class="email-input form-control mb-0"
                                            type="email"
                                            name="rows[{{ $i }}][email]"
                                            value="{{ $person['email'] }}"
                                            style="display:none; width:160px; padding:4px 6px;">
                                        <button type="button"
                                            onclick="toggleEmailEdit(this)"
                                            aria-label="Edit email"
                                            class="btn btn-secondary btn-sm"
                                            style="padding: 4px;">
                                            <svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                                <td>{{ $person['dietary'] ?? '-' }}</td>
                                <td>{{ $person['attend'] }}</td>

                                <td>
                                    <input type="hidden" name="rows[{{ $i }}][row_id]" value="{{ $person['row_id'] }}">
                                    <input type="text" name="rows[{{ $i }}][table_number]" value="{{ $person['table_number'] }}" class="form-control mb-0" style="width:70px;">
                                </td>

                                <td>
                                    <input type="text" name="rows[{{ $i }}][seat_number]" value="{{ $person['seat_number'] }}" class="form-control mb-0" style="width:70px;">
                                </td>

                                <td>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <span>{{ $person['unique_code'] ?? '-' }}</span>
                                        @if(!empty($person['unique_code']))
                                            <button type="button"
                                                onclick="copyCode(this.dataset.code)"
                                                data-code="{{ $person['unique_code'] }}"
                                                aria-label="Copy code"
                                                class="btn btn-secondary btn-sm"
                                                style="padding: 4px;">
                                                <svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <div style="display:flex; flex-wrap:wrap; gap:4px; align-items:center;">
                                        @if($person['source_type'] === 'RSVP' && !empty($rsvpId))
                                            {{-- Generate Code (Main RSVP Only) --}}
                                            <button type="submit"
                                                formaction="{{ route('admin.rsvp.generateCode', ['rsvp' => $rsvpId]) }}"
                                                formmethod="POST"
                                                class="btn btn-primary btn-sm">
                                                Gen Code
                                            </button>
                                        @endif

                                        @if(!empty($rsvpId) && ($person['source_type'] === 'RSVP' || ($person['source_type'] === 'Guest' && !empty($person['email']))))
                                            {{-- Send Email (RSVP or Guest with Email) --}}
                                            <button type="button"
                                                onclick="openSendCodeModal(this)"
                                                data-rsvp-id="{{ $rsvpId }}"
                                                data-email="{{ $person['email'] }}"
                                                data-name="{{ $person['contact_name'] }}"
                                                data-code="{{ $person['unique_code'] }}"
                                                class="btn btn-secondary btn-sm">
                                                Email
                                            </button>
                                        @endif

                                        @if($person['source_type'] === 'RSVP' && !empty($rsvpId))
                                            {{-- Delete RSVP (Main RSVP Only) --}}
                                            <button type="button"
                                                onclick="confirmDeleteRsvp('{{ $rsvpId }}')"
                                                aria-label="Delete RSVP"
                                                class="btn btn-danger btn-sm"
                                                style="padding: 6px;">
                                                <svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:20px; color:rgba(243, 236, 220, 0.7);">
                    <h5 style="margin:0 0 10px 0; letter-spacing:0.5px;">NOTES</h5>
                    <p style="margin:0 0 6px 0; font-size: 13px;">
                        Generate Code is only for RSVP (main guests). Additional guests will use the same unique code as their RSVP.
                    <br>
                        Invitation details will show both the RSVP and any additional guests together when extra guests are added in the RSVP form.</p>
                </div>

                <div class="mt-4" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        Save Assignments
                    </button>
                </div>
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
        <div style="background:var(--input-bg); padding:20px; border-radius:8px; width:320px; max-width:90%; color:#3d1516; text-align:center;">
            <h3 style="margin-top:0; margin-bottom:10px;">Success</h3>
            <p id="successMessage" style="margin:0 0 12px 0; font-size:13px; line-height:1.4;"></p>
            <button type="button"
                    onclick="closeSuccessModal()"
                    class="btn btn-secondary" style="border-color:#3d1516; color: #3d1516;">
                Close
            </button>
        </div>
    </div>

    {{-- EMAIL POPUP --}}
    <div id="sendCodeModal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; ">
        <div style="background:#fff; padding:20px; border-radius:8px; width:420px; max-width:90%; color:#3d1516; box-sizing:border-box;">
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
                        class="form-control"
                        style="background: #fff; border: 1px solid #ccc; color: #333;">
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

                <div style="display:flex; gap:8px; margin-top:15px; flex-wrap: wrap; justify-content: space-between;">
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <a id="sendGmailBtn"
                           href="#"
                           target="_blank" rel="noopener"
                           class="btn btn-secondary btn-sm" style="border-color:#3d1516; color: #3d1516;">
                            Gmail
                        </a>
                        <a id="sendOutlookBtn"
                           href="#"
                           target="_blank" rel="noopener"
                           class="btn btn-secondary btn-sm" style="border-color:#3d1516; color: #3d1516;">
                            Outlook
                        </a>
                    </div>
                    <div style="display:flex; gap:8px; justify-content:flex-end;">
                        <button type="button"
                                onclick="closeSendCodeModal()"
                                class="btn btn-sm" style="background:#eee; color:#333;">
                            Cancel
                        </button>
                        <button type="submit"
                                class="btn btn-primary btn-sm" style="background: #3d1516; color: #F3ECDC;">
                            Send
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.layout.footer')


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
