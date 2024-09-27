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
        Schema::table('trprogram', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->after('submit'); 
            $table->dropColumn('submit'); 
        });
    }

    public function down(): void
    {
        Schema::table('trprogram', function (Blueprint $table) {
            $table->boolean('submit')->default(false)->after('user_id'); 
            $table->dropColumn('status');
        });
    }
};
