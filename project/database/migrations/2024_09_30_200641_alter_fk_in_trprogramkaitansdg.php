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
        // Dropping the existing foreign key constraint on kaitansdg_id
        Schema::table('trprogramkaitansdg', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['kaitansdg_id']);

            // Now, re-add the foreign key with the new reference to mkaitansdg
            $table->foreign('kaitansdg_id')->references('id')->on('mkaitansdg')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting back the foreign key to the original table mkaitan_sdg
        Schema::table('trprogramkaitansdg', function (Blueprint $table) {
            // Drop the updated foreign key
            $table->dropForeign(['kaitansdg_id']);

            // Re-add the original foreign key constraint
            $table->foreign('kaitansdg_id')->references('id')->on('mkaitansdg')->onDelete('cascade');
        });
    }
};
