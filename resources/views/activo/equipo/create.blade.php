@extends ('menu')
@section('content')

    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- JS de Select2 -->
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }
    </style>

    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Equipo
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('recibo') }}"><button class="btn btn-primary"><i
                                    class="bi bi-arrow-90deg-left"></i></button></a>
                    </div>
                </div>

                <form method="POST" action="{{ route('equipo.store') }}">
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




                        <div class="row gy-4">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Marca</label>
                                <input type="text" class="form-control" name="marca"
                                    oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);"
                                    required>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Modelo:</label>
                                <input type="text" class="form-control" name="modelo"
                                    oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);"
                                    required>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Serie:</label>
                                <input type="text" class="form-control" name="serie"
                                    oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);"
                                    required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="dui" class="form-label">Color:</label>
                                <select class="form-select select2" name="color_id">
                                    <option value="">Seleccione</option>
                                    @foreach ($colores as $color)
                                        <option value="{{ $color->id }}">{{ $color->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="dui" class="form-label">Material:</label>
                                <select class="form-select select2" name="material_id">
                                    <option value="">Seleccione</option>
                                    @foreach ($materiales as $material)
                                        <option value="{{ $material->id }}">{{ $material->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="nit" class="form-label">Nit</label>
                                <input type="text" step="any" class="form-control" name="nit" id="nit"
                                    value="{{ old('nit') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="codigo_unico" class="form-label">Código Único</label>
                                <input type="text" class="form-control" name="codigo_unico" id="codigo_unico"
                                    value="{{ old('codigo_unico') }}">

                                <input type="hidden" class="form-control" name="codigo_credito" id="codigo_credito"
                                    value="{{ old('codigo_credito') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" name="nombres" id="nombres"
                                    value="{{ old('nombres') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" id="apellidos"
                                    value="{{ old('apellidos') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="telefono_contacto" class="form-label">No Telefono Contacto</label>
                                <input type="text" class="form-control" name="telefono_contacto" id="telefono_contacto"
                                    value="{{ old('telefono_contacto') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="nombre_propiedad" class="form-label">Nombre De La Propiedad</label>
                                <input type="text" class="form-control" name="nombre_propiedad" id="nombre_propiedad"
                                    value="{{ old('nombre_propiedad') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="tipo_inmueble" class="form-label">Tipo De Inmueble</label>
                                <input type="text" class="form-control" name="tipo_inmueble" id="tipo_inmueble"
                                    value="{{ old('tipo_inmueble') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="no_poligono" class="form-label">No Poligono</label>
                                <input type="text" class="form-control" name="no_poligono" id="no_poligono"
                                    value="{{ old('no_poligono') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="porcion" class="form-label">Porcion</label>
                                <input type="text" class="form-control" name="porcion" id="porcion"
                                    value="{{ old('porcion') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="otras_especificaciones" class="form-label">Otras Especificaciones</label>
                                <input type="text" class="form-control" name="otras_especificaciones"
                                    id="otras_especificaciones" value="{{ old('otras_especificaciones') }}">
                            </div>




                        </div>



                    </div>

                    <div class="card-footer" style="text-align: right">
                        <button class="btn btn-primary">Aceptar</button>
                    </div>

                </form>
            </div>
        </div>

    </div>


    <script>
        $(document).ready(function() {
            expandMenuAndHighlightOption('administracionMenu', 'reciboOption');
            // Inicializar todos los select con clase select2
            $('.select2').select2({
                placeholder: "Seleccione",
                allowClear: true,
                width: '100%'
            });
        });
    </script>


@endsection
