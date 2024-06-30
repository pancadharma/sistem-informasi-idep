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
        Schema::create('permission_role_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('id_role');
            $table->foreign('id_role', 'id_role_fk_9913741')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('id_permission');
            $table->foreign('id_permission', 'id_permission_fk_9913741')->references('id')->on('permissions')->onDelete('cascade');
        });
    }
};
