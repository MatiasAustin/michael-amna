<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->string('unique_code', 50)->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->dropColumn('unique_code');
        });
    }
};
