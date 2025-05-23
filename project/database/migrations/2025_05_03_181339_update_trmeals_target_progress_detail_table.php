<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trmeals_target_progress_detail', function (Blueprint $table) {
            // Ganti nama kolom dari id_target ke level
            $table->renameColumn('id_target', 'level');
        });

        Schema::table('trmeals_target_progress_detail', function (Blueprint $table) {
            // Ubah tipe level menjadi string
            $table->string('level')->change();

            // Jadikan status nullable
            $table->string('status', 50)->nullable()->change();

            // Tambahkan kolom polymorphic
            $table->unsignedBigInteger('targetable_id')->nullable()->after('id_meals_target_progress');
            $table->string('targetable_type')->nullable()->after('targetable_id');
            $table->index(['targetable_id', 'targetable_type'], 'targetable_index');            
        });
    }

    public function down(): void
    {
        Schema::table('trmeals_target_progress_detail', function (Blueprint $table) {
            // Kembalikan nama kolom dari level ke id_target
            $table->renameColumn('level', 'id_target');
        });

        Schema::table('trmeals_target_progress_detail', function (Blueprint $table) {
            $table->integer('id_target')->change();
            $table->string('status', 50)->nullable(false)->change();

            // Hapus index dan kolom polymorphic
            $table->dropIndex('targetable_index');
            $table->dropColumn(['targetable_id', 'targetable_type']);
        });
    }
};
