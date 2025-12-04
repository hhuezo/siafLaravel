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
        Schema::create('vehiculo', function (Blueprint $table) {
            $table->id();
            $table->string('oid')->nullable();
            $table->string('cod_anterior')->nullable();

            // Relaciones principales
            $table->foreignId('establecimiento_id')->nullable()->constrained('establecimiento');
            $table->foreignId('grupo_id')->nullable()->constrained('grupo');
            $table->foreignId('clase_id')->nullable()->constrained('clase');
            $table->foreignId('subclase_id')->nullable()->constrained('subclase');
            $table->string('correlativo')->nullable();
            $table->string('codigo_de_activo')->nullable();
            $table->foreignId('cuenta_contable_id')->nullable()->constrained('cuenta_contable');
            $table->foreignId('estado_fisico_id')->nullable()->constrained('estado_fisico');
            $table->dateTime('fecha_de_adquisicion')->nullable();
            $table->foreignId('procedencia_id')->nullable()->constrained('procedencia');
            $table->foreignId('fuente_id')->nullable()->constrained('fuente');
            $table->string('numero_de_factura')->nullable();
            $table->text('otra_caracteristica')->nullable();
            $table->text('observacion')->nullable();
            $table->decimal('valor_inicial', 20, 2)->nullable();
            $table->decimal('valor_actual', 20, 2)->nullable();
            $table->integer('vida_util')->nullable();
            $table->decimal('depreciacion', 20, 2)->nullable();
            $table->decimal('depreciacion_acumulada', 20, 2)->nullable();
            $table->decimal('depreciacion_diaria', 20, 2)->nullable();
            $table->foreignId('unidad_id')->nullable()->constrained('unidad');
            $table->foreignId('ambiente_id')->nullable()->constrained('ambiente');
            $table->foreignId('empleado_id')->nullable()->constrained('empleado');
            $table->foreignId('estado_id')->nullable()->constrained('estado_activo');
            $table->boolean('estado_activo_guardado')->default(false);
            $table->boolean('depreciado_totalmente')->default(false);
            $table->string('gc_record')->nullable();
            $table->decimal('valor_residual', 20, 2)->nullable();
            $table->decimal('valor_a_depreciar', 20, 2)->nullable();
            $table->integer('dias_a_depreciar')->nullable();
            $table->decimal('depreciacion_anual', 20, 2)->nullable();
            $table->decimal('depreciacion_mensual', 20, 2)->nullable();
            $table->integer('correlativo_int')->nullable();
            $table->text('detalle')->nullable();
            $table->boolean('no_depreciable')->default(0);
            $table->boolean('depresiable')->default(0);
            $table->decimal('valor_fijo', 20, 2)->default(0);

            // Campos específicos para vehículo
            $table->foreignId('marca_id')->nullable()->constrained('marca');
            $table->string('modelo')->nullable();
            $table->string('placa')->nullable();
            $table->foreignId('color_id')->nullable()->constrained('color');
            $table->string('motor')->nullable();
            $table->string('numero_chasis')->nullable();
            $table->integer('anio_fabricacion')->nullable();
            $table->foreignId('combustible_id')->nullable()->constrained('combustible');
            $table->foreignId('traccion_id')->nullable()->constrained('traccion');
            $table->string('equipo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculo');
    }
};
