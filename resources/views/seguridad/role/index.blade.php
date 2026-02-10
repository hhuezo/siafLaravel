@extends ('menu')
@section('content')
    <!-- DataTables CSS -->
    <link href="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="{{ asset('assets/libs/toast/toastr.min.css') }}" rel="stylesheet">

    <style>
        .offcanvas-permisos-role.offcanvas { width: 50% !important; max-width: 50% !important; }
        @media (max-width: 768px) { .offcanvas-permisos-role.offcanvas { width: 100% !important; max-width: 100% !important; } }
    </style>

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
                        Listado de roles
                    </div>
                    <div class="prism-toggle">
                        @can('create roles')
                            <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#drawer-create-role">Nuevo</button>
                        @endcan

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
                                    <th>Rol</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>

                                        <td>
                                            <button class="btn btn-sm btn-info btn-wave" data-bs-toggle="offcanvas"
                                                data-bs-target="#drawer-edit-role-{{ $item->id }}">
                                                &nbsp;<i class="ri-edit-line"></i>&nbsp;</button>
                                            &nbsp;
                                            <button class="btn btn-sm btn-secondary btn-wave" data-bs-toggle="offcanvas"
                                                data-bs-target="#drawer-permisos-{{ $item->id }}">
                                                &nbsp;<i class="ri-shield-keyhole-line"></i>&nbsp;</button>
                                        </td>
                                    </tr>
                                    @include('seguridad.role.drawer-edit')
                                    @include('seguridad.role.drawer-permisos')
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>


    @include('seguridad.role.drawer-create')




    <script src="{{ asset('assets/libs/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Activar DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('seguridadMenu', 'roleOption');

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

        function toggleUpdatePermission(roleId, permissionId) {
            fetch(`{{ url('seguridad/role/update_permission') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        role_id: roleId,
                        permission_id: permissionId,
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                    } else {
                        throw new Error(data.message || 'Acción fallida');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error(error.message || 'Error al actualizar permiso');
                });
        }
    </script>
    <!-- End:: row-1 -->
@endsection
