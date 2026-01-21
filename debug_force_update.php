<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = 2;
$newEmail = 'love@foreverkhoury.com';

echo "--- START ---\n";
try {
    // 1. Check existing
    $user = \App\Models\User::find($id);
    if (!$user) { die("User $id NOT FOUND\n"); }
    echo "Current: " . $user->email . "\n";

    // 2. Check collision
    $conflict = \App\Models\User::where('email', $newEmail)->first();
    if ($conflict) {
        echo "CONFLICT FOUND: ID " . $conflict->id . "\n";
        // Force delete specific ID
        \Illuminate\Support\Facades\DB::delete("DELETE FROM users WHERE id = ?", [$conflict->id]);
        echo "Conflict deleted (forced).\n";
    }

    // 3. Force Update Raw
    $affected = \Illuminate\Support\Facades\DB::update("UPDATE users SET email = ? WHERE id = ?", [$newEmail, $id]);
    echo "Rows affected: $affected\n";

    // 4. Verify
    // Clear cache/model state if needed (not needed for raw fetch)
    $freshUser = \Illuminate\Support\Facades\DB::selectOne("SELECT * FROM users WHERE id = ?", [$id]);
    echo "New Email (Raw): " . $freshUser->email . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
echo "--- END ---\n";
