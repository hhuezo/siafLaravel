<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipo', function (Blueprint $table) {
            $table->unsignedBigInteger('fuente_id')->nullable()->after('subclase_id');

            $table->foreign('fuente_id')
                ->references('id')
                ->on('fuente')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('equipo', function (Blueprint $table) {
            $table->dropForeign(['fuente_id']);
            $table->dropColumn('fuente_id');
        });
    }
};
