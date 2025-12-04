<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidad', function (Blueprint $table) {
            $table->id();
            $table->string('oid')->nullable();
            $table->string('gcrecord')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->string('descripcion')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidad');
    }
};
