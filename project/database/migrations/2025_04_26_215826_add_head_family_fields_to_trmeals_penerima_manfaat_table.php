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
        Schema::table('trmeals_penerima_manfaat', function (Blueprint $table) {
            $table->boolean('is_head_family')->default(false)->after('umur');
            $table->string('head_family_name')->nullable()->after('is_head_family');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trmeals_penerima_manfaat', function (Blueprint $table) {
            $table->dropColumn('is_head_family');
            $table->dropColumn('head_family_name');
        });
    }
};