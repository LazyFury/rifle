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
            // title 
            $table->string('title')->nullable(false)->comment('标题')->change();
            // desciption
            // $table->text('desciption')->comment('描述')->change();
            // key
            $table->string('key')->comment('key')->change();
            // list_api 
            $table->string('list_api')->comment('列表接口')->change();
            // create_api
            $table->string('create_api')->comment('新增接口')->change();
            // edit_api
            $table->string('update_api')->comment('编辑接口')->change();
            // delete_api
            $table->string('delete_api')->comment('删除接口')->change();
            // columns
            $table->json('columns')->comment('table columns')->change();
            // add_form_fields
            $table->json('add_form_fields')->comment('add_form_fields')->change();
            // search_form_fields
            $table->json('search_form_fields')->comment('search_form_fields')->change();
            // export_api
            $table->string('export_api')->nullable()->comment('导出接口')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
