<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $event_at_utc
 * @property string|null $headline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Countdown newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Countdown newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Countdown query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Countdown whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Countdown whereEventAtUtc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Countdown whereHeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Countdown whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Countdown whereUpdatedAt($value)
 */
	class Countdown extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rsvp_id
 * @property string $full_name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $table_number
 * @property string|null $seat_number
 * @property-read \App\Models\Rsvp $rsvp
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereRsvpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereSeatNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereTableNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereUpdatedAt($value)
 */
	class Guest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $filename
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Photo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Photo whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Photo whereUpdatedAt($value)
 */
	class Photo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $full_name
 * @property string|null $email
 * @property string $attend
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $table_number
 * @property string|null $seat_number
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Guest> $guests
 * @property-read int|null $guests_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereAttend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereSeatNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereTableNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rsvp whereUpdatedAt($value)
 */
	class Rsvp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $venue_location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue whereVenueLocation($value)
 */
	class Venue extends \Eloquent {}
}

