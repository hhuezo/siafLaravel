{{-- Drawer creación de usuario --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="drawer-create" aria-labelledby="drawer-create-label">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="drawer-create-label">Crear usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <form method="POST" action="{{ route('user.store') }}">
            @csrf
            <div class="row gy-3">
                <div class="col-12">
                    <label class="form-label">Nombre<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', '') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Usuario<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                        value="{{ old('username', '') }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Correo electrónico<sup class="text-danger">*</sup></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email', '') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Contraseña<sup class="text-danger">*</sup></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Rol<sup class="text-danger">*</sup></label>
                    <select class="form-select @error('role_id') is-invalid @enderror" name="role_id" required>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}" {{ old('role_id', '') == $rol->id ? 'selected' : '' }}>
                                {{ $rol->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
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
