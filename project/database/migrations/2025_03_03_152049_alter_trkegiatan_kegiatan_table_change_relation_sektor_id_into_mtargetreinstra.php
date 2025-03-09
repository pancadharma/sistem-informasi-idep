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
            Schema::table('trkegiatan_sektor', function (Blueprint $table) {
                $table->dropForeign(['sektor_id']);
                $table->unsignedBigInteger('sektor_id')->nullable()->change();
                $table->foreign('sektor_id')->references('id')->on('mtargetreinstra')->onDelete('cascade');
            });
    }

    public function down(): void
    {
        Schema::table('trkegiatan_sektor', function (Blueprint $table) {
                $table->dropForeign(['sektor_id']);
                $table->unsignedBigInteger('sektor_id')->nullable()->change();
                $table->foreign('sektor_id')->references('id')->on('msektor')->onDelete('cascade');
        });
    }
};