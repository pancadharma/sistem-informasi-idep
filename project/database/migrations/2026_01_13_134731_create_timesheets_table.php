<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();

            // owner
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // periode
            $table->smallInteger('year')->unsigned();
            $table->tinyInteger('month')->unsigned();

            // agregat
            $table->integer('total_minutes')->default(0);

            // workflow
            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'rejected'
            ])->default('draft');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();
            $table->text('approval_note')->nullable();

            $table->timestamps();

            // 1 user = 1 bulan
            $table->unique(['user_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
