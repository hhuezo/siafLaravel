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
        Schema::create('subclase', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->string('codigo')->nullable();
            $table->foreignId('clase_id')->nullable()->constrained('clase');
            $table->string('oid')->nullable();
            $table->string('gc_record')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subclase');
    }
};
