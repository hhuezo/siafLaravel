<div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalEditLabel">Modificar tipo de permiso</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('permission_type.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-12">
                            <label for="edit_name_{{ $item->id }}" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="name" id="edit_name_{{ $item->id }}"
                                value="{{ old('name', $item->name) }}" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active" id="edit_active_{{ $item->id }}"
                                    value="1" {{ old('active', $item->active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="edit_active_{{ $item->id }}">Activo</label>
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
