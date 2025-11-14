<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->string('table_number')->nullable()->after('message');
            $table->string('seat_number')->nullable()->after('table_number');
        });
    }

    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->dropColumn(['table_number', 'seat_number']);
        });
    }
};

