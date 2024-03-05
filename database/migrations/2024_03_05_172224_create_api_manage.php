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
        Schema::create('api_manages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("title")->nullable(false);
            $table->text("desciption");
            $table->string("key");
            // api,del_api,update_api,add_api
            $table->string("api");
            $table->string("del_api");
            $table->string("update_api");
            $table->string("add_api");
            $table->json("columns");//table columns
            // add_form_fields
            $table->json("add_form_fields");
            // search_form_fields
            $table->json("search_form_fields");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_manages');
    }
};
