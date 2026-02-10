{{-- Drawer asignación de Rol y Región (un drawer por usuario) --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="drawer-configurar-{{ $item->id }}"
    aria-labelledby="drawer-configurar-label-{{ $item->id }}">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="drawer-configurar-label-{{ $item->id }}">
            Configuración — {{ $item->name ?? $item->username }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold d-block mb-2">Roles</label>
                @foreach ($roles as $rol)
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" value="{{ $rol->id }}"
                            {{ $item->hasRole($rol->name) ? 'checked' : '' }}
                            onchange="toggleSyncRole({{ $item->id }}, {{ $rol->id }})"
                            id="role_{{ $rol->id }}_user_{{ $item->id }}">
                        <label class="form-check-label" for="role_{{ $rol->id }}_user_{{ $item->id }}">
                            {{ ucfirst($rol->name) }}
                        </label>
                    </div>
                @endforeach
            </div>

             </div>

        <div class="mt-4 pt-3 border-top">
            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="offcanvas">Cerrar</button>
        </div>
    </div>
</div>
