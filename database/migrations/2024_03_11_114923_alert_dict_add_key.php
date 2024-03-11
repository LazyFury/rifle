<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('dicts', function (Blueprint $table) {
            $table->string('key');
            $table->unique(['key', 'group_id']);
            // remove name and group_id unique
            $table->dropUnique(['name', 'group_id']);
        });

        Schema::table('dict_groups', function (Blueprint $table) {
            $table->string('key')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('dicts', function (Blueprint $table) {
            $table->dropUnique(['key', 'group_id']);
            $table->dropColumn('key');
        });

        Schema::table('dict_groups', function (Blueprint $table) {
            $table->dropColumn('key');
            $table->dropUnique('key');
        });
    }
};
