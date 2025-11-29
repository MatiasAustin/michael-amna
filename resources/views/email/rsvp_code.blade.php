{{-- resources/views/emails/rsvp_code.blade.php --}}
<p>Halo {{ $rsvp->full_name }},</p>

<p>This is your unique code for your invitation / RSVP:</p>

<p style="font-size:20px; font-weight:bold;">
    {{ $rsvp->unique_code }}
</p>

<p>You can use this link to see details:</p>

<p>
    <a href="{{ route('floormap', ['code' => $rsvp->unique_code]) }}#find">Click here to check invitation details</a>
</p>

<p>Thank You 🤍</p>
