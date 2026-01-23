@extends ('menu')
@section('content')

    <style>
        .form-control:read-only,
        .form-select:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
            border: 1px solid #ced4da;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
        }

        .tab-content {
            padding: 20px 0;
            min-height: 400px;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }

        .info-value {
            color: #212529;
            padding: 8px 12px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            min-height: 38px;
            display: flex;
            align-items: center;
        }
    </style>

    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        <i class="bi bi-eye me-2"></i>Ver Vehículo
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ route('vehiculo.edit', $vehiculo->id) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-fill me-1"></i>Editar
                        </a>
                        <a href="{{ url('vehiculo') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-90deg-left me-1"></i>Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="vehiculoTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="identificacion-tab" data-bs-toggle="tab"
                                data-bs-target="#identificacion" type="button" role="tab"
                                aria-controls="identificacion" aria-selected="true">
                                <i class="bi bi-info-circle me-1"></i>Identificación y Clasificación
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ubicacion-tab" data-bs-toggle="tab"
                                data-bs-target="#ubicacion" type="button" role="tab"
                                aria-controls="ubicacion" aria-selected="false">
                                <i class="bi bi-geo-alt me-1"></i>Ubicación e Información Financiera
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="caracteristicas-tab" data-bs-toggle="tab"
                                data-bs-target="#caracteristicas" type="button" role="tab"
                                aria-controls="caracteristicas" aria-selected="false">
                                <i class="bi bi-car-front me-1"></i>Características del Vehículo
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="vehiculoTabsContent">
                        <!-- Tab 1: Identificación y Clasificación -->
                        <div class="tab-pane fade show active" id="identificacion" role="tabpanel"
                            aria-labelledby="identificacion-tab">
                            <div class="row gy-4 mt-3">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Clase</div>
                                    <div class="info-value">
                                        {{ $vehiculo->clase->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Subclase</div>
                                    <div class="info-value">
                                        {{ $vehiculo->subclase->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <div class="info-label">Código activo</div>
                                    <div class="info-value">
                                        {{ $vehiculo->codigo_de_activo ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <div class="info-label">Vida útil (años)</div>
                                    <div class="info-value">
                                        {{ $vehiculo->vida_util ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Fuente</div>
                                    <div class="info-value">
                                        {{ $vehiculo->fuente->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Cuenta contable</div>
                                    <div class="info-value">
                                        {{ $vehiculo->cuentaContable->codigo ?? 'N/A' }}
                                        @if($vehiculo->cuentaContable && $vehiculo->cuentaContable->descripcion)
                                            - {{ $vehiculo->cuentaContable->descripcion }}
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <div class="info-label">Estado</div>
                                    <div class="info-value">
                                        {{ $vehiculo->estado->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <div class="info-label">Procedencia</div>
                                    <div class="info-value">
                                        {{ $vehiculo->procedencia->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <div class="info-label">Establecimiento</div>
                                    <div class="info-value">
                                        {{ $vehiculo->establecimiento->codigo ?? 'N/A' }}
                                        @if($vehiculo->establecimiento && $vehiculo->establecimiento->descripcion)
                                            - {{ $vehiculo->establecimiento->descripcion }}
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <div class="info-label">Grupo</div>
                                    <div class="info-value">
                                        {{ $vehiculo->grupo->codigo ?? 'N/A' }}
                                        @if($vehiculo->grupo && $vehiculo->grupo->descripcion)
                                            - {{ $vehiculo->grupo->descripcion }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Ubicación e Información Financiera -->
                        <div class="tab-pane fade" id="ubicacion" role="tabpanel" aria-labelledby="ubicacion-tab">
                            <div class="row gy-4 mt-3">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Unidad</div>
                                    <div class="info-value">
                                        {{ $vehiculo->unidad->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Fecha de adquisición</div>
                                    <div class="info-value">
                                        {{ $vehiculo->fecha_de_adquisicion ? \Carbon\Carbon::parse($vehiculo->fecha_de_adquisicion)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Ambiente</div>
                                    <div class="info-value">
                                        {{ $vehiculo->ambiente->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Valor inicial</div>
                                    <div class="info-value">
                                        ${{ number_format($vehiculo->valor_inicial ?? 0, 2, '.', ',') }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Empleado</div>
                                    <div class="info-value">
                                        {{ $vehiculo->empleado->nombre ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Número de factura</div>
                                    <div class="info-value">
                                        {{ $vehiculo->numero_de_factura ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Opciones de depreciación</div>
                                    <div class="info-value">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" disabled
                                                {{ $vehiculo->depresiable ? 'checked' : '' }}>
                                            <label class="form-check-label">Depreciable</label>
                                        </div>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" disabled
                                                {{ $vehiculo->no_depreciable ? 'checked' : '' }}>
                                            <label class="form-check-label">No depreciable</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Valor actual</div>
                                    <div class="info-value">
                                        ${{ number_format($vehiculo->valor_actual ?? 0, 2, '.', ',') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Características del Vehículo -->
                        <div class="tab-pane fade" id="caracteristicas" role="tabpanel"
                            aria-labelledby="caracteristicas-tab">
                            <div class="row gy-4 mt-3">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Marca</div>
                                    <div class="info-value">
                                        {{ $vehiculo->marca->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Modelo</div>
                                    <div class="info-value">
                                        {{ $vehiculo->modelo ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Placa</div>
                                    <div class="info-value">
                                        {{ $vehiculo->placa ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Color</div>
                                    <div class="info-value">
                                        {{ $vehiculo->color->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Motor</div>
                                    <div class="info-value">
                                        {{ $vehiculo->motor ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Número de chasis</div>
                                    <div class="info-value">
                                        {{ $vehiculo->numero_chasis ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="info-label">Año de fabricación</div>
                                    <div class="info-value">
                                        {{ $vehiculo->anio_fabricacion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="info-label">Combustible</div>
                                    <div class="info-value">
                                        {{ $vehiculo->combustible->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="info-label">Tracción</div>
                                    <div class="info-value">
                                        {{ $vehiculo->traccion->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Equipo</div>
                                    <div class="info-value">
                                        {{ $vehiculo->equipo ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="info-label">Estado Físico</div>
                                    <div class="info-value">
                                        {{ $vehiculo->estadoFisico->descripcion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="info-label">Otra característica</div>
                                    <div class="info-value" style="min-height: auto; padding: 12px;">
                                        {{ $vehiculo->otra_caracteristica ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="info-label">Observación</div>
                                    <div class="info-value" style="min-height: auto; padding: 12px;">
                                        {{ $vehiculo->observacion ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="info-label">Detalle / descripción</div>
                                    <div class="info-value" style="min-height: auto; padding: 12px;">
                                        {{ $vehiculo->detalle ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer" style="text-align: right">
                    <a href="{{ route('vehiculo.edit', $vehiculo->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-fill me-1"></i>Editar Vehículo
                    </a>
                    <a href="{{ url('vehiculo') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-90deg-left me-1"></i>Volver
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            expandMenuAndHighlightOption('activosMenu', 'vehiculoOption');
        });
    </script>

@endsection
