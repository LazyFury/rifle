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
        Schema::table('menus', function (Blueprint $table) {
            $table->string('path')->default('')->comment('路由');
            // icon 
            $table->string('icon')->default('')->comment('图标');
            // change desciption to nullable 
            $table->string('desciption')->nullable(true)->change();
            // add meta_id default null 
            $table->unsignedBigInteger('meta_id')->nullable(true)->default(null)->comment('meta id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('path');
            $table->dropColumn('icon');
            $table->string('desciption')->nullable(false)->change();
        });
    }
};
