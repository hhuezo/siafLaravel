@extends ('menu')
@section('content')
    <link href="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/toast/toastr.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/libs/toast/toastr.min.js') }}"></script>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Listado de tipos de permiso</div>
                    <div class="prism-toggle">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">Nuevo</button>
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
                        <script>toastr.success("{{ session('success') }}");</script>
                    @endif
                    @if (session('error'))
                        <script>toastr.error("{{ session('error') }}");</script>
                    @endif

                    <div class="table-responsive">
                        <table id="datatable-basic" class="table table-striped text-nowrap w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Activo</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissionTypes as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->active ? 'Sí' : 'No' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-wave" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit-{{ $item->id }}">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-wave" data-bs-toggle="modal"
                                                data-bs-target="#modal-delete-{{ $item->id }}">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @include('seguridad.permission_type.edit')
                                    @include('seguridad.permission_type.delete')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modalCreateLabel">Crear tipo de permiso</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('permission_type.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-4">
                            <div class="col-12">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">Activo</label>
                                </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('seguridadMenu', 'permissionTypeOption');
            $('#datatable-basic').DataTable({
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    zeroRecords: "No se encontraron resultados",
                    emptyTable: "Ningún dato disponible en esta tabla",
                    paginate: { first: "<<", previous: "<", next: ">", last: ">>" }
                }
            });
        });
    </script>
@endsection
