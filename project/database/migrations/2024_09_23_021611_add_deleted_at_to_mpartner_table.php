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
        Schema::table('mpartner', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * this file created by this command as example
     * php artisan make:migration add_deleted_at_to_mpartner_table --table=mpartner
     */
    public function down(): void
    {
        Schema::table('mpartner', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
