<div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLgLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Modificar ambiente</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('ambiente.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">


                    <div class="row gy-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" name="descripcion"
                                value="{{ $item->descripcion }}" required>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Unidad</label>
                            <select name="unidad_id" class="form-select">
                                @foreach ($unidades as $unidad)
                                    <option value="{{ $unidad->id }}" {{$unidad->id == $item->unidad_id ? 'selected':''}}>{{ $unidad->descripcion }}</option>
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
