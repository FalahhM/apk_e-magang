<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dospems', function (Blueprint $table) {
            $table->unsignedBigInteger('pengajuan_id')->nullable()->after('user_id');
            
            $table->foreign('pengajuan_id')
                  ->references('id')
                  ->on('pengajuans')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dospems', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_id']);
            $table->dropColumn('pengajuan_id');
        });
    }
};
