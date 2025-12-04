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
        Schema::create('empleado', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nombre')->nullable();
            $table->foreignId('ambiente_id')->nullable()->constrained('ambiente')->onDelete('set null');
            $table->foreignId('gerencia_id')->nullable()->constrained('gerencia')->onDelete('set null');
            $table->foreignId('departamento_id')->nullable()->constrained('departamento')->onDelete('set null');
            $table->boolean('activo')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('gc_record')->nullable();
            $table->string('oid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado');
    }
};
