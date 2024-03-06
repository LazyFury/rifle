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
            $table->renameColumn('api', 'list_api')->comment('列表接口');
            $table->renameColumn('add_api', 'create_api')->comment('新增接口');
            // $table->renameColumn('update_api', 'edit_api')->comment('编辑接口');
            $table->renameColumn('del_api', 'delete_api')->comment('删除接口');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('api_manages', function (Blueprint $table) {
            $table->renameColumn('list_api', 'api')->comment('列表接口');
            $table->renameColumn('create_api', 'add_api')->comment('新增接口');
            // $table->renameColumn('edit_api', 'update_api')->comment('编辑接口');
            $table->renameColumn('delete_api', 'del_api')->comment('删除接口');
        });
    }
};
