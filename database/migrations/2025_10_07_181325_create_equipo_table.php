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
        Schema::create('equipo', function (Blueprint $table) {
            $table->id();
            $table->string('oid')->nullable();
            $table->string('cod_anterior')->nullable();

            // Relaciones FK
            $table->foreignId('establecimiento_id')->nullable()->constrained('establecimiento');
            $table->foreignId('grupo_id')->nullable()->constrained('grupo');
            $table->foreignId('clase_id')->nullable()->constrained('clase');
            $table->foreignId('subclase_id')->nullable()->constrained('subclase');
            $table->foreignId('cuenta_contable_id')->nullable()->constrained('cuenta_contable');
            $table->foreignId('estado_fisico_id')->nullable()->constrained('estado_fisico');
            $table->foreignId('procedencia_id')->nullable()->constrained('procedencia');
            $table->foreignId('unidad_id')->nullable()->constrained('unidad');
            $table->foreignId('ambiente_id')->nullable()->constrained('ambiente');
            $table->foreignId('empleado_id')->nullable()->constrained('empleado');
            $table->foreignId('estado_id')->nullable()->constrained('estado_activo');
             $table->foreignId('fuente_id')->nullable()->constrained('fuente');
            // Relaciones FK existentes
            $table->foreignId('color_id')->nullable()->constrained('color');
            $table->foreignId('material_id')->nullable()->constrained('material');

            // Campos generales
            $table->string('correlativo')->nullable();
            $table->string('codigo_activo')->nullable();
            $table->string('marca')->nullable();  // string
            $table->string('modelo')->nullable(); // string
            $table->string('serie')->nullable();
            $table->date('fecha_adquisicion')->nullable();

            $table->string('numero_factura')->nullable();
            $table->text('otra_caracteristica')->nullable();
            $table->text('observacion')->nullable();
            $table->decimal('valor_inicial', 15, 2)->nullable();
            $table->decimal('valor_actual', 15, 2)->nullable();
            $table->integer('vida_util')->nullable();
            $table->decimal('depreciacion', 15, 2)->nullable();
            $table->decimal('depreciacion_acumulada', 15, 2)->nullable();
            $table->decimal('depreciacion_diaria', 15, 2)->nullable();
            $table->boolean('estado_activo_guardado')->default(false);
            $table->boolean('depreciado_totalmente')->default(false);
            $table->decimal('valor_residual', 15, 2)->nullable();
            $table->decimal('valor_a_depreciar', 15, 2)->nullable();
            $table->integer('dias_a_depreciar')->nullable();
            $table->decimal('depreciacion_anual', 15, 2)->nullable();
            $table->decimal('depreciacion_mensual', 15, 2)->nullable();
            $table->integer('correlativo_int')->nullable();
            $table->text('detalle')->nullable();
            $table->boolean('no_depreciable')->default(false);
            $table->boolean('depresiable')->default(true);
            $table->boolean('valor_fijo')->default(false);

            $table->string('gc_record')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo');
    }
};
