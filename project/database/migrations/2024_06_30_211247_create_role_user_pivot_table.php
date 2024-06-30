<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_user_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user', 'id_user_fk_9913750')->references('id')->on('muser')->onDelete('cascade');
            $table->unsignedBigInteger('id_role');
            $table->foreign('id_role', 'id_role_fk_9913750')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user_pivot');
    }
};
