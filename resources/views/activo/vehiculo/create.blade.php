@extends ('menu')
@section('content')

    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- JS de Select2 -->
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }

        .form-control:read-only {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
            pointer-events: none;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
            pointer-events: auto;
        }

        .nav-tabs .nav-link.completed {
            color: #198754;
            pointer-events: auto;
        }

        .tab-content {
            padding: 20px 0;
            min-height: 400px;
        }

        .wizard-footer {
            border-top: 1px solid #dee2e6;
            padding: 15px 0;
            margin-top: 20px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #dee2e6;
            z-index: 0;
        }

        .step:last-child::after {
            display: none;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            position: relative;
            z-index: 1;
            font-weight: 600;
        }

        .step.active .step-number {
            background: #0d6efd;
            color: white;
        }

        .step.completed .step-number {
            background: #198754;
            color: white;
        }

        .step.completed .step-number::before {
            content: '✓';
        }
    </style>

    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Nuevo vehículo
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('vehiculo') }}"><button class="btn btn-primary"><i
                                    class="bi bi-arrow-90deg-left"></i></button></a>
                    </div>
                </div>

                <form method="POST" action="{{ route('vehiculo.store') }}" id="vehiculoForm">
                    @csrf
                    <div class="card-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Wizard Steps Indicator -->
                        <div class="step-indicator">
                            <div class="step active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-title">Identificación y Clasificación</div>
                            </div>
                            <div class="step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-title">Ubicación e Información Financiera</div>
                            </div>
                            <div class="step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-title">Características del Vehículo</div>
                            </div>
                        </div>

                        <!-- Tabs Navigation (Hidden, used for Bootstrap tab functionality) -->
                        <ul class="nav nav-tabs d-none" id="vehiculoTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="identificacion-tab" data-bs-toggle="tab"
                                    data-bs-target="#identificacion" type="button" role="tab"
                                    aria-controls="identificacion" aria-selected="true"></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ubicacion-tab" data-bs-toggle="tab"
                                    data-bs-target="#ubicacion" type="button" role="tab"
                                    aria-controls="ubicacion" aria-selected="false"></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="caracteristicas-tab" data-bs-toggle="tab"
                                    data-bs-target="#caracteristicas" type="button" role="tab"
                                    aria-controls="caracteristicas" aria-selected="false"></button>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content" id="vehiculoTabsContent">
                            <!-- Tab 1: Identificación y Clasificación -->
                            <div class="tab-pane fade show active" id="identificacion" role="tabpanel"
                                aria-labelledby="identificacion-tab">
                                <div class="row gy-4">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="clase_id" class="form-label">Clase</label>
                                        <select name="clase_id" id="clase_id" class="form-select select2"
                                            onchange="loadSubclases(this.value); limpiarCamposCalculados();">
                                            <option value="">Seleccione</option>
                                            @foreach ($clases as $clase)
                                                <option value="{{ $clase->id }}" @selected(old('clase_id') == $clase->id)>
                                                    {{ $clase->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="subclase_id" class="form-label">Subclase</label>
                                        <select name="subclase_id" id="subclase_id" class="form-select select2"
                                            onchange="generarCodigoActivo(this.value)">
                                            <option value="">Seleccione una clase primero</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <label for="codigo_de_activo" class="form-label">Código activo</label>
                                        <input type="text" class="form-control" name="codigo_de_activo" id="codigo_de_activo"
                                            value="{{ old('codigo_de_activo') }}" readonly>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <label for="vida_util" class="form-label">Vida útil (años)</label>
                                        <input type="number" min="1" class="form-control" name="vida_util" id="vida_util"
                                            value="{{ old('vida_util') }}" readonly>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="fuente_id" class="form-label">Fuente</label>
                                        <select name="fuente_id" id="fuente_id" class="form-select select2">
                                            <option value="">Seleccione</option>
                                            @foreach ($fuentes as $fuente)
                                                <option value="{{ $fuente->id }}" @selected(old('fuente_id') == $fuente->id)>
                                                    {{ $fuente->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="cuenta_contable_id" class="form-label">Cuenta contable</label>
                                        <select name="cuenta_contable_id" id="cuenta_contable_id" class="form-select select2">
                                            <option value="">Seleccione</option>
                                            @foreach ($cuentas as $cuenta)
                                                <option value="{{ $cuenta->id }}"
                                                    @selected(old('cuenta_contable_id') == $cuenta->id)>
                                                    {{ $cuenta->codigo }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <label for="estado_id" class="form-label">Estado</label>
                                        <select class="form-select select2" name="estado_id" id="estado_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($estadosActivos as $estadoActivo)
                                                <option value="{{ $estadoActivo->id }}"
                                                    @selected(old('estado_id') == $estadoActivo->id)>
                                                    {{ $estadoActivo->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <label for="procedencia_id" class="form-label">Procedencia</label>
                                        <select class="form-select select2" name="procedencia_id" id="procedencia_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($procedencias as $procedencia)
                                                <option value="{{ $procedencia->id }}"
                                                    @selected(old('procedencia_id') == $procedencia->id)>
                                                    {{ $procedencia->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Ubicación e Información Financiera -->
                            <div class="tab-pane fade" id="ubicacion" role="tabpanel" aria-labelledby="ubicacion-tab">
                                <div class="row gy-4">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="unidad_id" class="form-label">Unidad</label>
                                        <select name="unidad_id" id="unidad_id" class="form-select select2">
                                            <option value="">Seleccione</option>
                                            @foreach ($unidades as $unidad)
                                                <option value="{{ $unidad->id }}" @selected(old('unidad_id') == $unidad->id)>
                                                    {{ $unidad->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="fecha_de_adquisicion" class="form-label">Fecha de adquisición</label>
                                        <input type="date" class="form-control" name="fecha_de_adquisicion"
                                            id="fecha_de_adquisicion" value="{{ old('fecha_de_adquisicion', date('Y-m-d')) }}">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="ambiente_id" class="form-label">Ambiente</label>
                                        <select name="ambiente_id" id="ambiente_id" class="form-select select2">
                                            <option value="">Seleccione una unidad primero</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="valor_inicial" class="form-label">Valor inicial</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control" name="valor_inicial"
                                                id="valor_inicial" value="{{ old('valor_inicial') }}" placeholder="0.00"
                                                min="0">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="empleado_id" class="form-label">Empleado</label>
                                        <select name="empleado_id" id="empleado_id" class="form-select select2">
                                            <option value="">Seleccione un ambiente primero</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="numero_de_factura" class="form-label">Número de factura</label>
                                        <input type="text" class="form-control" name="numero_de_factura"
                                            id="numero_de_factura" value="{{ old('numero_de_factura') }}"
                                            placeholder="Número de factura">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label class="form-label d-block">Opciones de depreciación</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="depresiable"
                                                id="depresiable" value="1" {{ old('depresiable') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="depresiable">
                                                Depreciable
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="no_depreciable"
                                                id="no_depreciable" value="1" {{ old('no_depreciable') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="no_depreciable">
                                                No depreciable
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 3: Características del Vehículo -->
                            <div class="tab-pane fade" id="caracteristicas" role="tabpanel"
                                aria-labelledby="caracteristicas-tab">
                                <div class="row gy-4">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="marca_id" class="form-label">Marca</label>
                                        <select class="form-select select2" name="marca_id" id="marca_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($marcas as $marca)
                                                <option value="{{ $marca->id }}" @selected(old('marca_id') == $marca->id)>
                                                    {{ $marca->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="modelo" class="form-label">Modelo</label>
                                        <input type="text" class="form-control" name="modelo" id="modelo"
                                            value="{{ old('modelo') }}"
                                            oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="placa" class="form-label">Placa</label>
                                        <input type="text" class="form-control" name="placa" id="placa"
                                            value="{{ old('placa') }}"
                                            oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="color_id" class="form-label">Color</label>
                                        <select class="form-select select2" name="color_id" id="color_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($colores as $color)
                                                <option value="{{ $color->id }}" @selected(old('color_id') == $color->id)>
                                                    {{ $color->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="motor" class="form-label">Motor</label>
                                        <input type="text" class="form-control" name="motor" id="motor"
                                            value="{{ old('motor') }}"
                                            oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="numero_chasis" class="form-label">Número de chasis</label>
                                        <input type="text" class="form-control" name="numero_chasis" id="numero_chasis"
                                            value="{{ old('numero_chasis') }}"
                                            oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="anio_fabricacion" class="form-label">Año de fabricación</label>
                                        <input type="number" min="1900" max="{{ date('Y') + 1 }}" class="form-control"
                                            name="anio_fabricacion" id="anio_fabricacion" value="{{ old('anio_fabricacion') }}"
                                            placeholder="Ej: 2024">
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="combustible_id" class="form-label">Combustible</label>
                                        <select class="form-select select2" name="combustible_id" id="combustible_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($combustibles as $combustible)
                                                <option value="{{ $combustible->id }}"
                                                    @selected(old('combustible_id') == $combustible->id)>
                                                    {{ $combustible->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="traccion_id" class="form-label">Tracción</label>
                                        <select class="form-select select2" name="traccion_id" id="traccion_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($tracciones as $traccion)
                                                <option value="{{ $traccion->id }}"
                                                    @selected(old('traccion_id') == $traccion->id)>
                                                    {{ $traccion->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="equipo" class="form-label">Equipo</label>
                                        <input type="text" class="form-control" name="equipo" id="equipo"
                                            value="{{ old('equipo') }}"
                                            oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="estado_fisico_id" class="form-label">Estado Físico</label>
                                        <select class="form-select" name="estado_fisico_id" id="estado_fisico_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($estadosFisicos as $estadoFisico)
                                                <option value="{{ $estadoFisico->id }}"
                                                    @selected(old('estado_fisico_id') == $estadoFisico->id)>
                                                    {{ $estadoFisico->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="otra_caracteristica" class="form-label">Otra característica</label>
                                        <textarea name="otra_caracteristica" id="otra_caracteristica" rows="3"
                                            class="form-control"
                                            placeholder="Ingrese otras características del vehículo...">{{ old('otra_caracteristica') }}</textarea>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="observacion" class="form-label">Observación</label>
                                        <textarea name="observacion" id="observacion" rows="3" class="form-control"
                                            placeholder="Ingrese observaciones adicionales...">{{ old('observacion') }}</textarea>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="detalle" class="form-label">Detalle / descripción</label>
                                        <textarea name="detalle" id="detalle" rows="3" class="form-control"
                                            placeholder="Ingrese información adicional sobre el vehículo...">{{ old('detalle') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Wizard Navigation Footer -->
                    <div class="card-footer wizard-footer">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                                <i class="bi bi-arrow-left me-1"></i>Anterior
                            </button>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-primary" id="nextBtn">
                                    Siguiente<i class="bi bi-arrow-right ms-1"></i>
                                </button>
                                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                    <i class="bi bi-check-circle me-1"></i>Guardar Vehículo
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        $(document).ready(function() {
            expandMenuAndHighlightOption('activosMenu', 'vehiculoOption');

            // Inicializar todos los select con clase select2
            $('.select2').select2({
                placeholder: "Seleccione",
                allowClear: true,
                width: '100%'
            });

            // Asegurar que todos los select2 tengan el mismo ancho que los inputs
            $('.select2').on('select2:open', function() {
                $(this).data('select2').$container.css('width', '100%');
            });

            // Si venimos de una validación con errores, recargar datos
            @if (old('clase_id'))
                loadSubclases("{{ old('clase_id') }}", "{{ old('subclase_id') }}");
            @endif

            @if (old('unidad_id'))
                loadAmbientes("{{ old('unidad_id') }}", "{{ old('ambiente_id') }}");
            @endif

            @if (old('ambiente_id'))
                loadEmpleados("{{ old('ambiente_id') }}", "{{ old('empleado_id') }}");
            @endif

            // Evento para cargar ambientes cuando se selecciona una unidad
            $('#unidad_id').on('change', function() {
                const unidadId = $(this).val();
                loadAmbientes(unidadId);
            });

            // Evento para cargar empleados cuando se selecciona un ambiente
            $('#ambiente_id').on('change', function() {
                const ambienteId = $(this).val();
                loadEmpleados(ambienteId);
            });

            // Wizard Navigation
            $('#nextBtn').on('click', function() {
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });

            $('#prevBtn').on('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Validación del formulario antes de enviar
            $('#vehiculoForm').on('submit', function(e) {
                if (!validateStep(currentStep)) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        function showStep(step) {
            // Ocultar todos los panes
            $('.tab-pane').removeClass('show active');

            // Mostrar el pane correspondiente
            const panes = ['identificacion', 'ubicacion', 'caracteristicas'];
            $('#' + panes[step - 1]).addClass('show active');

            // Actualizar tabs de Bootstrap
            const tabButtons = ['identificacion-tab', 'ubicacion-tab', 'caracteristicas-tab'];
            $('.nav-link').removeClass('active');
            $('#' + tabButtons[step - 1]).addClass('active');

            // Actualizar indicador de pasos
            $('.step').each(function(index) {
                const stepNum = index + 1;
                $(this).removeClass('active completed');
                if (stepNum < step) {
                    $(this).addClass('completed');
                } else if (stepNum === step) {
                    $(this).addClass('active');
                }
            });

            // Actualizar botones de navegación
            $('#prevBtn').toggle(step > 1);
            $('#nextBtn').toggle(step < totalSteps);
            $('#submitBtn').toggle(step === totalSteps);
        }

        function validateStep(step) {
            let isValid = true;
            const requiredFields = {
                1: ['clase_id', 'subclase_id', 'codigo_de_activo', 'vida_util', 'fuente_id', 'cuenta_contable_id', 'estado_id', 'procedencia_id'],
                2: ['unidad_id', 'fecha_de_adquisicion', 'valor_inicial', 'ambiente_id'],
                3: ['estado_fisico_id']
            };

            // Limpiar errores previos
            $('.is-invalid').removeClass('is-invalid');

            if (requiredFields[step]) {
                requiredFields[step].forEach(function(fieldId) {
                    const field = $('#' + fieldId);
                    if (!field.val() || field.val() === '') {
                        isValid = false;
                        field.addClass('is-invalid');
                    }
                });
            }

            if (!isValid) {
                toastr.warning('Por favor, complete todos los campos requeridos en este paso.');
            }

            return isValid;
        }

        function loadSubclases(id, selectedId = null) {
            const subclaseSelect = document.getElementById('subclase_id');
            subclaseSelect.innerHTML = '<option value="">Cargando...</option>';

            if (!id) {
                subclaseSelect.innerHTML = '<option value="">Seleccione una clase primero</option>';
                return;
            }

            const url = `{{ route('vehiculo.loadSubclases', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener subclases');
                    }
                    return response.json();
                })
                .then(data => {
                    subclaseSelect.innerHTML = '<option value="">Seleccione una subclase</option>';

                    data.forEach(subclase => {
                        const option = document.createElement('option');
                        option.value = subclase.id;
                        option.textContent = subclase.descripcion;
                        if (selectedId && String(selectedId) === String(subclase.id)) {
                            option.selected = true;
                        }
                        subclaseSelect.appendChild(option);
                    });

                    // Re-inicializar select2
                    $(subclaseSelect).trigger('change');
                })
                .catch(error => {
                    console.error(error);
                    subclaseSelect.innerHTML = '<option value="">Error al cargar subclases</option>';
                });
        }

        function loadAmbientes(id, selectedId = null, empleadoId = null) {
            const ambienteSelect = $('#ambiente_id');

            if (!id) {
                ambienteSelect.empty().append('<option value="">Seleccione una unidad primero</option>');
                ambienteSelect.trigger('change');
                // Limpiar también empleados
                $('#empleado_id').empty().append('<option value="">Seleccione un ambiente primero</option>').trigger('change');
                return;
            }

            // Mostrar estado de carga
            ambienteSelect.empty().append('<option value="">Cargando...</option>').trigger('change');

            const url = `{{ route('vehiculo.loadAmbientes', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener ambientes');
                    }
                    return response.json();
                })
                .then(data => {
                    // Limpiar y agregar opción por defecto
                    ambienteSelect.empty().append('<option value="">Seleccione un ambiente</option>');

                    // Agregar ambientes
                    data.forEach(ambiente => {
                        const option = $('<option></option>')
                            .attr('value', ambiente.id)
                            .text(ambiente.descripcion);

                        if (selectedId && String(selectedId) === String(ambiente.id)) {
                            option.attr('selected', true);
                        }

                        ambienteSelect.append(option);
                    });

                    // Actualizar Select2
                    ambienteSelect.trigger('change');

                    // Si hay ambiente seleccionado y empleadoId, cargar empleados
                    if (selectedId && empleadoId) {
                        loadEmpleados(selectedId, empleadoId);
                    }
                })
                .catch(error => {
                    console.error(error);
                    ambienteSelect.empty().append('<option value="">Error al cargar ambientes</option>').trigger('change');
                });
        }

        function loadEmpleados(id, selectedId = null) {
            const empleadoSelect = $('#empleado_id');

            if (!id) {
                empleadoSelect.empty().append('<option value="">Seleccione un ambiente primero</option>');
                empleadoSelect.trigger('change');
                return;
            }

            // Mostrar estado de carga
            empleadoSelect.empty().append('<option value="">Cargando...</option>').trigger('change');

            const url = `{{ route('vehiculo.loadEmpleados', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener empleados');
                    }
                    return response.json();
                })
                .then(data => {
                    // Limpiar y agregar opción por defecto
                    empleadoSelect.empty().append('<option value="">Seleccione un empleado</option>');

                    // Agregar empleados
                    data.forEach(empleado => {
                        const option = $('<option></option>')
                            .attr('value', empleado.id)
                            .text(empleado.nombre);

                        if (selectedId && String(selectedId) === String(empleado.id)) {
                            option.attr('selected', true);
                        }

                        empleadoSelect.append(option);
                    });

                    // Actualizar Select2
                    empleadoSelect.trigger('change');
                })
                .catch(error => {
                    console.error(error);
                    empleadoSelect.empty().append('<option value="">Error al cargar empleados</option>').trigger('change');
                });
        }

        function limpiarCamposCalculados() {
            const codigoActivoInput = document.getElementById('codigo_de_activo');
            const vidaUtilInput = document.getElementById('vida_util');

            if (codigoActivoInput) {
                codigoActivoInput.value = '';
            }
            if (vidaUtilInput) {
                vidaUtilInput.value = '';
            }
        }

        function generarCodigoActivo(subclaseId) {
            const codigoActivoInput = document.getElementById('codigo_de_activo');
            const vidaUtilInput = document.getElementById('vida_util');

            if (!subclaseId) {
                limpiarCamposCalculados();
                return;
            }

            // Mostrar indicador de carga
            codigoActivoInput.value = 'Generando...';
            codigoActivoInput.disabled = true;
            if (vidaUtilInput) {
                vidaUtilInput.value = 'Generando...';
                vidaUtilInput.disabled = true;
            }

            const url = `{{ route('vehiculo.generarCodigo', ['subclaseId' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', subclaseId);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al generar el código');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.codigo_de_activo) {
                        codigoActivoInput.value = data.codigo_de_activo;

                        // Actualizar vida útil si está disponible (incluso si es null o 0)
                        if (vidaUtilInput) {
                            vidaUtilInput.value = data.vida_util !== null && data.vida_util !== undefined ? data.vida_util : '';
                        }
                    } else {
                        codigoActivoInput.value = '';
                        if (vidaUtilInput) {
                            vidaUtilInput.value = '';
                        }
                        toastr.error('No se pudo generar el código automáticamente');
                    }
                    codigoActivoInput.disabled = false;
                    if (vidaUtilInput) {
                        vidaUtilInput.disabled = false;
                    }
                })
                .catch(error => {
                    console.error(error);
                    codigoActivoInput.value = '';
                    if (vidaUtilInput) {
                        vidaUtilInput.value = '';
                    }
                    codigoActivoInput.disabled = false;
                    if (vidaUtilInput) {
                        vidaUtilInput.disabled = false;
                    }
                    toastr.error('Error al generar el código. Por favor, verifique que la subclase tenga todos los datos necesarios.');
                });
        }
    </script>

@endsection
