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
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
        }

        .tab-content {
            padding: 20px 0;
            min-height: 400px;
        }
    </style>

    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Editar equipo
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('equipo') }}"><button class="btn btn-primary"><i
                                    class="bi bi-arrow-90deg-left"></i></button></a>
                    </div>
                </div>

                <form method="POST" action="{{ route('equipo.update', $equipo->id) }}" id="equipoForm">
                    @csrf
                    @method('PUT')
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

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs" id="equipoTabs" role="tablist">
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
                                    <i class="bi bi-box-seam me-1"></i>Características Físicas e Información Adicional
                                </button>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content" id="equipoTabsContent">
                            <!-- Tab 1: Identificación y Clasificación -->
                            <div class="tab-pane fade show active" id="identificacion" role="tabpanel"
                                aria-labelledby="identificacion-tab">
                                <div class="row gy-4">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="clase_id" class="form-label">Clase</label>
                                        <select name="clase_id" id="clase_id" class="form-select select2"
                                            onchange="loadSubclases(this.value)">
                                            <option value="">Seleccione</option>
                                            @foreach ($clases as $clase)
                                                <option value="{{ $clase->id }}"
                                                    @selected(old('clase_id', $equipo->clase_id) == $clase->id)>
                                                    {{ $clase->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="subclase_id" class="form-label">Subclase</label>
                                        <select name="subclase_id" id="subclase_id" class="form-select select2">
                                            <option value="">Seleccione una clase primero</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <label for="codigo_activo" class="form-label">Código activo</label>
                                        <input type="text" class="form-control" name="codigo_activo" id="codigo_activo"
                                            value="{{ old('codigo_activo', $equipo->codigo_activo) }}">
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <label for="vida_util" class="form-label">Vida útil (años)</label>
                                        <input type="number" min="1" class="form-control" name="vida_util" id="vida_util"
                                            value="{{ old('vida_util', $equipo->vida_util) }}">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="fuente_id" class="form-label">Fuente</label>
                                        <select name="fuente_id" id="fuente_id" class="form-select select2">
                                            <option value="">Seleccione</option>
                                            @foreach ($fuentes as $fuente)
                                                <option value="{{ $fuente->id }}"
                                                    @selected(old('fuente_id', $equipo->fuente_id) == $fuente->id)>
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
                                                    @selected(old('cuenta_contable_id', $equipo->cuenta_contable_id) == $cuenta->id)>
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
                                                    @selected(old('estado_id', $equipo->estado_id) == $estadoActivo->id)>
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
                                                    @selected(old('procedencia_id', $equipo->procedencia_id) == $procedencia->id)>
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
                                                <option value="{{ $unidad->id }}"
                                                    @selected(old('unidad_id', $equipo->unidad_id) == $unidad->id)>
                                                    {{ $unidad->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="fecha_adquisicion" class="form-label">Fecha de adquisición</label>
                                        <input type="date" class="form-control" name="fecha_adquisicion"
                                            id="fecha_adquisicion"
                                            value="{{ old('fecha_adquisicion', $equipo->fecha_adquisicion ? \Carbon\Carbon::parse($equipo->fecha_adquisicion)->format('Y-m-d') : '') }}">
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
                                                id="valor_inicial" value="{{ old('valor_inicial', $equipo->valor_inicial) }}"
                                                placeholder="0.00" min="0">
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
                                            id="numero_de_factura" value="{{ old('numero_de_factura', $equipo->numero_de_factura) }}"
                                            placeholder="Número de factura">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label class="form-label d-block">Opciones de depreciación</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="depresiable"
                                                id="depresiable" value="1"
                                                {{ old('depresiable', $equipo->depresiable) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="depresiable">
                                                Depreciable
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="no_depreciable"
                                                id="no_depreciable" value="1"
                                                {{ old('no_depreciable', $equipo->no_depreciable) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="no_depreciable">
                                                No depreciable
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 3: Características Físicas e Información Adicional -->
                            <div class="tab-pane fade" id="caracteristicas" role="tabpanel"
                                aria-labelledby="caracteristicas-tab">
                                <div class="row gy-4">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="marca" class="form-label">Marca</label>
                                        <input type="text" class="form-control" name="marca" id="marca"
                                            value="{{ old('marca', $equipo->marca) }}"
                                            oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="modelo" class="form-label">Modelo</label>
                                        <input type="text" class="form-control" name="modelo" id="modelo"
                                            value="{{ old('modelo', $equipo->modelo) }}"
                                            oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="serie" class="form-label">Serie</label>
                                        <input type="text" class="form-control" name="serie" id="serie"
                                            value="{{ old('serie', $equipo->serie) }}"
                                            oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="color_id" class="form-label">Color</label>
                                        <select class="form-select select2" name="color_id" id="color_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($colores as $color)
                                                <option value="{{ $color->id }}"
                                                    @selected(old('color_id', $equipo->color_id) == $color->id)>
                                                    {{ $color->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="material_id" class="form-label">Material</label>
                                        <select class="form-select select2" name="material_id" id="material_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($materiales as $material)
                                                <option value="{{ $material->id }}"
                                                    @selected(old('material_id', $equipo->material_id) == $material->id)>
                                                    {{ $material->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="estado_fisico_id" class="form-label">Estado Físico</label>
                                        <select class="form-select select2" name="estado_fisico_id" id="estado_fisico_id">
                                            <option value="">Seleccione</option>
                                            @foreach ($estadosFisicos as $estadoFisico)
                                                <option value="{{ $estadoFisico->id }}"
                                                    @selected(old('estado_fisico_id', $equipo->estado_fisico_id) == $estadoFisico->id)>
                                                    {{ $estadoFisico->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="otra_caracteristica" class="form-label">Otra característica</label>
                                        <textarea name="otra_caracteristica" id="otra_caracteristica" rows="3"
                                            class="form-control"
                                            placeholder="Ingrese otras características del equipo...">{{ old('otra_caracteristica', $equipo->otra_caracteristica) }}</textarea>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="observacion" class="form-label">Observación</label>
                                        <textarea name="observacion" id="observacion" rows="3" class="form-control"
                                            placeholder="Ingrese observaciones adicionales...">{{ old('observacion', $equipo->observacion) }}</textarea>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label for="detalle" class="form-label">Detalle / descripción</label>
                                        <textarea name="detalle" id="detalle" rows="3" class="form-control"
                                            placeholder="Ingrese información adicional sobre el equipo...">{{ old('detalle', $equipo->detalle) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer" style="text-align: right">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Actualizar Equipo
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            expandMenuAndHighlightOption('activosMenu', 'equipoOption');

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

            // Cargar datos relacionados del equipo
            const equipoClaseId = {{ $equipo->clase_id ?? 'null' }};
            const equipoSubclaseId = {{ $equipo->subclase_id ?? 'null' }};
            const equipoUnidadId = {{ $equipo->unidad_id ?? 'null' }};
            const equipoAmbienteId = {{ $equipo->ambiente_id ?? 'null' }};
            const equipoEmpleadoId = {{ $equipo->empleado_id ?? 'null' }};

            // Variable para controlar si es carga inicial
            let isInitialLoad = true;

            // Cargar subclases si hay clase seleccionada
            if (equipoClaseId) {
                loadSubclases(equipoClaseId, equipoSubclaseId);
            }

            // Cargar ambientes si hay unidad seleccionada
            if (equipoUnidadId) {
                loadAmbientes(equipoUnidadId, equipoAmbienteId, equipoEmpleadoId);
            }

            // Evento para cargar ambientes cuando se selecciona una unidad
            $('#unidad_id').on('change', function() {
                const unidadId = $(this).val();
                loadAmbientes(unidadId);
            });

            // Evento para cargar empleados cuando se selecciona un ambiente
            $('#ambiente_id').on('change', function() {
                if (!isInitialLoad) {
                    const ambienteId = $(this).val();
                    loadEmpleados(ambienteId);
                }
            });

            // Marcar que la carga inicial terminó después de un pequeño delay
            setTimeout(function() {
                isInitialLoad = false;
            }, 1000);

            // Validación del formulario antes de enviar
            $('#equipoForm').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        function validateForm() {
            let isValid = true;
            
            // Campos requeridos según las mismas reglas del create
            const requiredFields = {
                // Tab 1: Todos los campos son requeridos
                tab1: ['clase_id', 'subclase_id', 'codigo_activo', 'vida_util', 'fuente_id', 'cuenta_contable_id', 'estado_id', 'procedencia_id'],
                // Tab 2: Solo estos son requeridos (empleado_id, numero_de_factura, depresiable, no_depreciable NO son requeridos)
                tab2: ['unidad_id', 'fecha_adquisicion', 'valor_inicial', 'ambiente_id'],
                // Tab 3: Solo estado_fisico_id es requerido
                tab3: ['estado_fisico_id']
            };

            // Limpiar errores previos
            $('.is-invalid').removeClass('is-invalid');

            // Validar campos de la Tab 1
            requiredFields.tab1.forEach(function(fieldId) {
                const field = $('#' + fieldId);
                if (!field.val() || field.val() === '') {
                    isValid = false;
                    field.addClass('is-invalid');
                }
            });

            // Validar campos de la Tab 2
            requiredFields.tab2.forEach(function(fieldId) {
                const field = $('#' + fieldId);
                if (!field.val() || field.val() === '') {
                    isValid = false;
                    field.addClass('is-invalid');
                }
            });

            // Validar campos de la Tab 3
            requiredFields.tab3.forEach(function(fieldId) {
                const field = $('#' + fieldId);
                if (!field.val() || field.val() === '') {
                    isValid = false;
                    field.addClass('is-invalid');
                }
            });

            if (!isValid) {
                toastr.warning('Por favor, complete todos los campos requeridos antes de guardar.');
                // Cambiar a la primera tab que tenga errores
                if ($('#identificacion .is-invalid').length > 0) {
                    $('#identificacion-tab').tab('show');
                } else if ($('#ubicacion .is-invalid').length > 0) {
                    $('#ubicacion-tab').tab('show');
                } else if ($('#caracteristicas .is-invalid').length > 0) {
                    $('#caracteristicas-tab').tab('show');
                }
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

            const url = `{{ route('equipo.loadSubclases', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);

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

            const url = `{{ route('equipo.loadAmbientes', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);

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

            const url = `{{ route('equipo.loadEmpleados', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);

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
    </script>

@endsection
