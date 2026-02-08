@extends('menu')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Reporte de inventario - Mobiliario y equipo
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Seleccione los filtros opcionales y la fecha de corte para generar el inventario en PDF (equipos en estado Disponible o Asignado).</p>

                    <form method="POST" action="{{ route('equipo.reporteInventario') }}" target="_blank">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="clase_id" class="form-label">Clase</label>
                                <select name="clase_id" id="clase_id" onchange="loadSubclases(this.value)" class="form-select">
                                    <option value="">Seleccione</option>
                                    @foreach ($clases as $clase)
                                        <option value="{{ $clase->id }}">{{ $clase->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="subclase_id" class="form-label">Subclase</label>
                                <select name="subclase_id" id="subclase_id" class="form-select">
                                    <option value="">Seleccione una clase primero</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fuente_id" class="form-label">Fuente</label>
                                <select name="fuente_id" id="fuente_id" class="form-select">
                                    <option value="">Seleccione</option>
                                    @foreach ($fuentes as $fuente)
                                        <option value="{{ $fuente->id }}">{{ $fuente->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="ambiente_id" class="form-label">Ambiente</label>
                                <select name="ambiente_id" id="ambiente_id" class="form-select" onchange="loadEmpleados(this.value)">
                                    <option value="">Seleccione</option>
                                    @foreach ($ambientes as $ambiente)
                                        <option value="{{ $ambiente->id }}">{{ $ambiente->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="empleado_id" class="form-label">Empleado</label>
                                <select name="empleado_id" id="empleado_id" class="form-select">
                                    <option value="">Seleccione un ambiente primero</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="cuenta_id" class="form-label">Cuenta contable</label>
                                <select name="cuenta_id" id="cuenta_id" class="form-select">
                                    <option value="">Seleccione</option>
                                    @foreach ($cuentas as $cuenta)
                                        <option value="{{ $cuenta->id }}">{{ $cuenta->codigo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fechaFinal" class="form-label">Fecha de corte (hasta)</label>
                                <input type="date" name="fechaFinal" id="fechaFinal" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" name="descargar" value="1" id="descargar_pdf" class="form-check-input">
                                    <label for="descargar_pdf" class="form-check-label">Descargar PDF (si no se marca, se abre en el navegador)</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-file-earmark-pdf me-1"></i>Generar reporte
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('reportesMenu', 'reporteInventarioEquipoOption');
        });

        function loadSubclases(id) {
            const subclaseSelect = document.getElementById('subclase_id');
            subclaseSelect.innerHTML = '<option value="">Cargando...</option>';

            if (!id) {
                subclaseSelect.innerHTML = '<option value="">Seleccione una clase primero</option>';
                return;
            }

            const url = `{{ route('equipo.loadSubclases', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Error al obtener subclases');
                    return response.json();
                })
                .then(data => {
                    subclaseSelect.innerHTML = '<option value="">Seleccione una subclase</option>';
                    data.forEach(subclase => {
                        const option = document.createElement('option');
                        option.value = subclase.id;
                        option.textContent = subclase.descripcion;
                        subclaseSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    subclaseSelect.innerHTML = '<option value="">Error al cargar subclases</option>';
                });
        }

        function loadEmpleados(id) {
            const empleadoSelect = document.getElementById('empleado_id');
            empleadoSelect.innerHTML = '<option value="">Cargando...</option>';

            if (!id) {
                empleadoSelect.innerHTML = '<option value="">Seleccione un ambiente primero</option>';
                return;
            }

            const url = `{{ route('equipo.loadEmpleados', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Error al obtener empleados');
                    return response.json();
                })
                .then(data => {
                    empleadoSelect.innerHTML = '<option value="">Seleccione un empleado</option>';
                    data.forEach(empleado => {
                        const option = document.createElement('option');
                        option.value = empleado.id;
                        option.textContent = empleado.nombre;
                        empleadoSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    empleadoSelect.innerHTML = '<option value="">Error al cargar empleados</option>';
                });
        }
    </script>
@endsection
