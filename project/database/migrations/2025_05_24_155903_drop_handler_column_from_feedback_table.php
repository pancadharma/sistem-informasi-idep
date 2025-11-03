<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropHandlerColumnFromFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Hanya hapus jika Anda yakin kolom 'handler' (string) masih ada
            if (Schema::hasColumn('feedback', 'handler')) {
                $table->dropColumn('handler');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Jika Anda ingin bisa mengembalikan kolom handler (string) saat rollback
            // Sesuaikan tipe data dan atribut lainnya jika perlu
            $table->string('handler')->nullable()->after('kontak_penerima'); // Sesuaikan posisi 'after' jika perlu
        });
    }
}