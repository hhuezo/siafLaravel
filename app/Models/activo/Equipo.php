<?php

namespace App\Models\activo;

use App\Models\catalogo\Ambiente;
use App\Models\catalogo\Clase;
use App\Models\catalogo\Color;
use App\Models\catalogo\CuentaContable;
use App\Models\catalogo\Empleado;
use App\Models\catalogo\EstadoActivo;
use App\Models\catalogo\EstadoFisico;
use App\Models\catalogo\Fuente;
use App\Models\catalogo\Material;
use App\Models\catalogo\Procedencia;
use App\Models\catalogo\SubClase;
use App\Models\catalogo\Unidad;
use App\Models\general\Establecimiento;
use App\Models\general\Grupo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipo extends Model
{
    protected $table = 'equipo';

    protected $fillable = [
        'oid',
        'cod_anterior',
        'establecimiento_id',
        'grupo_id',
        'clase_id',
        'subclase_id',
        'correlativo',
        'codigo_activo',
        'cuenta_contable_id',
        'estado_fisico_id',
        'fecha_adquisicion',
        'procedencia_id',
        'fuente_id',
        'numero_de_factura',
        'otra_caracteristica',
        'observacion',
        'valor_inicial',
        'valor_actual',
        'vida_util',
        'depreciacion',
        'depreciacion_acumulada',
        'depreciacion_diaria',
        'unidad_id',
        'ambiente_id',
        'empleado_id',
        'estado_id',
        'estado_activo_guardado',
        'depreciado_totalmente',
        'gc_record',
        'valor_residual',
        'valor_a_depreciar',
        'dias_a_depreciar',
        'depreciacion_anual',
        'depreciacion_mensual',
        'correlativo_int',
        'detalle',
        'no_depreciable',
        'depresiable',
        'valor_fijo',
        'marca',
        'modelo',
        'serie',
        'color_id',
        'material_id'
    ];

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class);
    }

    public function subclase(): BelongsTo
    {
        return $this->belongsTo(SubClase::class);
    }

    public function cuentaContable(): BelongsTo
    {
        return $this->belongsTo(CuentaContable::class, 'cuenta_contable_id');
    }

    public function estadoFisico(): BelongsTo
    {
        return $this->belongsTo(EstadoFisico::class, 'estado_fisico_id');
    }

    public function procedencia(): BelongsTo
    {
        return $this->belongsTo(Procedencia::class);
    }

    public function fuente(): BelongsTo
    {
        return $this->belongsTo(Fuente::class, 'fuente_id');
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    public function ambiente(): BelongsTo
    {
        return $this->belongsTo(Ambiente::class);
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoActivo::class, 'estado_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

}
