<div class="modal fade" id="modal-delete-{{ $item->id }}" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalDeleteLabel">Eliminar tipo de permiso</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('permission_type.destroy', $item->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="mb-0">¿Está seguro que desea eliminar el tipo de permiso "{{ $item->name }}"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
