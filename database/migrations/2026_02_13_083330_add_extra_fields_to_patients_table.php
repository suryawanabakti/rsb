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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('pangkat')->nullable()->after('gender');
            $table->string('nrp_nip', 50)->nullable()->after('pangkat');
            $table->string('pendidikan_terakhir')->nullable()->after('nrp_nip');
            $table->string('jabatan_kesatuan')->nullable()->after('pendidikan_terakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['pangkat', 'nrp_nip', 'pendidikan_terakhir', 'jabatan_kesatuan']);
        });
    }
};
