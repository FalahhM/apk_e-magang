<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactPersonIdToPengajuansTable extends Migration
{
    public function up()
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_person_id')->nullable()->after('user_id');
            
            // Tambahkan index untuk foreign key
            $table->foreign('contact_person_id')->references('id')->on('contact_person')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropForeign(['contact_person_id']);
            $table->dropColumn('contact_person_id');
        });
    }
}
