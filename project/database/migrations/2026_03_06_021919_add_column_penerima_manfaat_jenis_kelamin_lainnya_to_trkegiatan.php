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
        Schema::table('trkegiatan', function (Blueprint $table) {
            $table->integer('penerimamanfaatdewasalainnya')->nullable()->after('penerimamanfaatdewasalakilaki');
            $table->integer('penerimamanfaatlansialainnya')->nullable()->after('penerimamanfaatlansialakilaki');
            $table->integer('penerimamanfaatremajalainnya')->nullable()->after('penerimamanfaatremajalakilaki');
            $table->integer('penerimamanfaatanaklainnya')->nullable()->after('penerimamanfaatanaklakilaki');

            $table->integer('penerimamanfaatdisabilitaslainnya')->nullable()->after('penerimamanfaatdisabilitaslakilaki');
            $table->integer('penerimamanfaatnondisabilitaslainnya')->nullable()->after('penerimamanfaatnondisabilitaslakilaki');
            $table->integer('penerimamanfaatmarjinallainnya')->nullable()->after('penerimamanfaatmarjinallakilaki');
            $table->integer('penerimamanfaatlainnyatotal')->nullable()->after('penerimamanfaatlakilakitotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatan', function (Blueprint $table) {
            $table->dropColumn('penerimamanfaatdewasalainnya');
            $table->dropColumn('penerimamanfaatlansialainnya');
            $table->dropColumn('penerimamanfaatremajalainnya');
            $table->dropColumn('penerimamanfaatanaklainnya');
            $table->dropColumn('penerimamanfaatdisabilitaslainnya');
            $table->dropColumn('penerimamanfaatnondisabilitaslainnya');
            $table->dropColumn('penerimamanfaatmarjinallainnya');
            $table->dropColumn('penerimamanfaatlainnyatotal');
        });
    }
};
