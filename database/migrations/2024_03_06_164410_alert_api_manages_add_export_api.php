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
        Schema::table('api_manages', function (Blueprint $table) {
            $table->string('export_api')->nullable()->comment('导出接口');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('api_manages', function (Blueprint $table) {
            $table->dropColumn('export_api');
        });
    }
};
