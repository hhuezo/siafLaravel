<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ambiente', function (Blueprint $table) {
            $table->id();
            $table->string('oid')->nullable();
            $table->string('gc_record')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('descripcion');
            $table->foreignId('unidad_id')->nullable()->constrained('unidad');
            $table->string('unidad_oid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambiente');
    }
};
