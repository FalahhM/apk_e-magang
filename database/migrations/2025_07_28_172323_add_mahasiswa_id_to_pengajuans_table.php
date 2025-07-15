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
    Schema::table('pengajuans', function (Blueprint $table) {
        $table->unsignedBigInteger('mahasiswa_id')->after('id');

        $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('pengajuans', function (Blueprint $table) {
        $table->dropForeign(['mahasiswa_id']);
        $table->dropColumn('mahasiswa_id');
    });
}

};
