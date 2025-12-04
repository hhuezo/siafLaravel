@extends ('menu')
@section('content')
    <!-- DataTables CSS -->
    <link href="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="{{ asset('assets/libs/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('assets/libs/toast/toastr.min.js') }}"></script>



    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Vehiculos
                    </div>
                    <div class="prism-toggle">
                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modal-reporte-inventario">Nuevo</button>
                    </div>
                </div>
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

                    @if (session('success'))
                        <script>
                            toastr.success("{{ session('success') }}");
                        </script>
                    @endif

                    @if (session('error'))
                        <script>
                            toastr.error("{{ session('error') }}");
                        </script>
                    @endif

                    <div class="table-responsive">
                        <table id="datatable-basic" class="table table-striped text-nowrap w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Codigo</th>
                                    <th>Clase</th>
                                    <th>Subclase</th>
                                    <th>Empleado</th>
                                    <th>Equipo</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Placa</th>
                                    <th>Color</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehiculos as $vehiculo)
                                    <tr>
                                        <td>{{ $vehiculo->codigo_de_activo ?? '-' }}</td>
                                        <td>{{ $vehiculo->clase->descripcion ?? '-' }}</td>
                                        <td>{{ $vehiculo->subclase->descripcion ?? '-' }}</td>
                                        <td>{{ $vehiculo->empleado->nombre ?? '-' }}</td>
                                        <td>{{ $vehiculo->equipo ?? '-' }}</td>
                                        <td>{{ $vehiculo->marca->descripcion ?? '-' }}</td>
                                        <td>{{ $vehiculo->modelo ?? '-' }}</td>
                                        <td>{{ $vehiculo->placa ?? '-' }}</td>
                                        <td>{{ $vehiculo->color->descripcion ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('vehiculo.show', $vehiculo->id) }}"
                                                    class="btn btn-sm btn-primary">Ver</a>
                                                <a href="{{ route('vehiculo.edit', $vehiculo->id) }}"
                                                    class="btn btn-sm btn-warning">Editar</a>
                                                <form action="{{ route('vehiculo.destroy', $vehiculo->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Desea eliminar este registro?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>


    <div class="modal fade" id="modal-reporte-inventario" tabindex="-1" aria-labelledby="exampleModalLgLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Reporte</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('vehiculo.reporteInventario') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-3">

                            <div class="col-6">
                                <label for="nombre" class="form-label">Clase</label>
                                <select name="clase_id" onchange="loadSubclases(this.value)" class="form-select">
                                    <option value="">Seleccione</option>
                                    @foreach ($clases as $clase)
                                        <option value="{{ $clase->id }}">{{ $clase->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="nombre" class="form-label">Fuente</label>
                                <select name="fuente_id" class="form-select">
                                    <option value="">Seleccione</option>
                                    @foreach ($fuentes as $fuente)
                                        <option value="{{ $fuente->id }}">{{ $fuente->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="nombre" class="form-label">Sub clase</label>
                                <select name="subclase_id" id="subclase_id" class="form-select">
                                </select>
                            </div>



                            <div class="col-6">
                                <label for="nombre" class="form-label">Fecha desde</label>
                                <input type="date" name="fechaInicio" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>



                            <div class="col-6">
                                <label for="nombre" class="form-label">Ambiente</label>

                                <select name="ambiente_id" class="form-select" onchange="loadEmpleados(this.value)">
                                    <option value="">Seleccione</option>
                                    @foreach ($ambientes as $ambiente)
                                        <option value="{{ $ambiente->id }}">{{ $ambiente->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-6">
                                <label for="nombre" class="form-label">Fecha hasta</label>
                                <input type="date" name="fechaFinal" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>



                            <div class="col-6">
                                <label for="nombre" class="form-label">Empledo</label>
                                <select name="empleado_id" id="empleado_id" class="form-select">
                                </select>
                            </div>



                            <div class="col-6">
                                <label for="nombre" class="form-label">Cuenta contable</label>
                                <select name="cuenta_id" class="form-select">
                                    <option value="">Seleccione</option>
                                    @foreach ($cuentas as $cuenta)
                                        <option value="{{ $cuenta->id }}">{{ $cuenta->codigo }}</option>
                                    @endforeach
                                </select>
                            </div>




                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script src="{{ asset('assets/libs/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Activar DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('catalogoMenu', 'bancoOption');

            $('#datatable-basic').DataTable({
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    infoPostFix: "",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron resultados",
                    emptyTable: "Ningún dato disponible en esta tabla",
                    paginate: {
                        first: "<<",
                        previous: "<",
                        next: ">",
                        last: ">>"
                    },
                    aria: {
                        sortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sortDescending: ": Activar para ordenar la columna de manera descendente"
                    },
                    buttons: {
                        copy: 'Copiar',
                        colvis: 'Visibilidad',
                        print: 'Imprimir',
                        excel: 'Exportar Excel',
                        pdf: 'Exportar PDF'
                    }
                }
            });

        });

        function loadSubclases(id) {
            const subclaseSelect = document.getElementById('subclase_id');
            subclaseSelect.innerHTML = '<option value="">Cargando...</option>';

            if (!id) {
                subclaseSelect.innerHTML = '<option value="">Seleccione una clase primero</option>';
                return;
            }

            const url = `{{ route('equipo.loadSubclases', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER',
                id);

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
                        subclaseSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error(error);
                    subclaseSelect.innerHTML = '<option value="">Error al cargar subclases</option>';
                });
        }



        function loadEmpleados(id) {
            const empleadoSelect = document.getElementById('empleado_id'); // 👈 nombre corregido
            empleadoSelect.innerHTML = '<option value="">Cargando...</option>';

            if (!id) {
                empleadoSelect.innerHTML = '<option value="">Seleccione</option>';
                return;
            }

            const url = `{{ route('equipo.loadEmpleados', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener empleados');
                    }
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
                .catch(error => {
                    console.error(error);
                    empleadoSelect.innerHTML = '<option value="">Error al cargar empleados</option>';
                });
        }
    </script>
    <!-- End:: row-1 -->
@endsection
