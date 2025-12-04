<div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLgLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Modificar Banco</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('banco.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row gy-3">

                        <div class="col-12">
                            <label for="nombre_{{ $item->id }}" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre_{{ $item->id }}" name="nombre"
                                   value="{{ old('nombre', $item->nombre) }}" required
                                   oninput="const start = this.selectionStart; const end = this.selectionEnd; this.value = this.value.toUpperCase(); this.setSelectionRange(start, end);">
                        </div>


                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="activo_{{ $item->id }}"
                                       name="activo" value="1"
                                       {{ old('activo', $item->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo_{{ $item->id }}">Activo</label>
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
