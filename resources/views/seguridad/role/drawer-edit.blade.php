{{-- Drawer edición de rol --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="drawer-edit-role-{{ $item->id }}"
    aria-labelledby="drawer-edit-role-label-{{ $item->id }}">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="drawer-edit-role-label-{{ $item->id }}">Modificar rol</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <form method="POST" action="{{ route('role.update', $item->id) }}">
            @method('PUT')
            @csrf
            <div class="row gy-3">
                <div class="col-12">
                    <label class="form-label">Nombre del rol<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $item->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
