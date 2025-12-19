<div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLgLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Modificar Empleado</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('empleado.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">


                    <div class="row gy-4">


                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label class="form-label">Código</label>
                                <input type="number" name="codigo" class="form-control" min="0"
                                    value="{{ old('codigo', $item->codigo ?? '') }}">
                            </div>


                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="{{ old('nombre') }}"
                                    required>
                            </div>



                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Ambiente</label>
                                <select name="ambiente_id" class="form-select">
                                    @foreach ($ambientes as $ambiente)
                                        <option value="{{ $ambiente->id }}">{{ $ambiente->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Gerencia</label>
                                <select name="gerencia_id" class="form-select">
                                    @foreach ($gerencias as $gerencia)
                                        <option value="{{ $gerencia->id }}">{{ $gerencia->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Departamento</label>
                                <select name="departamento_id" class="form-select">
                                    @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Activo</label>
                                <select name="activo" class="form-select">
                                    <option value="1" {{ old('activo', $item->activo ?? '') == 1 ? 'selected' : '' }}>
                                        Activo</option>
                                    <option value="0" {{ old('activo', $item->activo ?? '') == 0 ? 'selected' : '' }}>
                                        Inactivo</option>
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
