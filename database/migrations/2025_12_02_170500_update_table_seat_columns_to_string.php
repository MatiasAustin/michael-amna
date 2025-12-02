<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();

        // On MySQL, explicitly widen to VARCHAR so mixed text/number values are accepted.
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE rsvps MODIFY table_number VARCHAR(50) NULL");
            DB::statement("ALTER TABLE rsvps MODIFY seat_number VARCHAR(50) NULL");
            DB::statement("ALTER TABLE guests MODIFY table_number VARCHAR(50) NULL");
            DB::statement("ALTER TABLE guests MODIFY seat_number VARCHAR(50) NULL");
        }

        // On SQLite the original migration used string columns already (stored as TEXT),
        // so no change is required.
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // Revert to a conservative numeric type (tinyint) if needed.
            DB::statement("ALTER TABLE rsvps MODIFY table_number TINYINT UNSIGNED NULL");
            DB::statement("ALTER TABLE rsvps MODIFY seat_number TINYINT UNSIGNED NULL");
            DB::statement("ALTER TABLE guests MODIFY table_number TINYINT UNSIGNED NULL");
            DB::statement("ALTER TABLE guests MODIFY seat_number TINYINT UNSIGNED NULL");
        }
    }
};
