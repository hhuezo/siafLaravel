{{-- Drawer edición de usuario --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="drawer-edit-{{ $item->id }}"
    aria-labelledby="drawer-edit-label-{{ $item->id }}">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="drawer-edit-label-{{ $item->id }}">Modificar usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <form method="POST" action="{{ route('user.update', $item->id) }}">
            @method('PUT')
            @csrf
            <div class="row gy-3">
                <div class="col-12">
                    <label class="form-label">Usuario<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                        value="{{ old('username', $item->username) }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Nombre<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $item->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Correo electrónico<sup class="text-danger">*</sup></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email', $item->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Cargo<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control @error('cargo') is-invalid @enderror" name="cargo"
                        value="{{ old('cargo', $item->cargo) }}" required>
                    @error('cargo')
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
