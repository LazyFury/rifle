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

        Schema::create('dict_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('description')->nullable();
        });

        Schema::create('dicts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('value');
            $table->string('description')->nullable();
            // group_id
            $table->foreignId('group_id')->nullable()->constrained('dict_groups')->nullOnDelete();
            $table->unique(['name', 'group_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dicts');
        Schema::dropIfExists('dict_groups');
    }
};
