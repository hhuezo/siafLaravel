{{-- Drawer asignación de permisos por rol — agrupados por tipo de permiso --}}
<div class="offcanvas offcanvas-end offcanvas-permisos-role" tabindex="-1" id="drawer-permisos-{{ $item->id }}"
    aria-labelledby="drawer-permisos-label-{{ $item->id }}">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="drawer-permisos-label-{{ $item->id }}">
            Permisos — {{ $item->name }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <p class="text-muted small mb-3">Activa o desactiva los permisos para este rol. Agrupados por tipo.</p>

        @foreach ($permissionTypes as $tipo)
            @if ($tipo->permissions->isNotEmpty())
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-1 mb-2">
                        <span class="badge bg-primary-transparent text-primary me-1">{{ $tipo->name }}</span>
                    </h6>
                    <div class="row g-2 g-md-3">
                        @foreach ($tipo->permissions as $permiso)
                            <div class="col-12 col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value="{{ $permiso->id }}"
                                        id="permiso_{{ $permiso->id }}_role_{{ $item->id }}"
                                        {{ $item->hasPermissionTo($permiso->name) ? 'checked' : '' }}
                                        onchange="toggleUpdatePermission({{ $item->id }}, {{ $permiso->id }})">
                                    <label class="form-check-label" for="permiso_{{ $permiso->id }}_role_{{ $item->id }}">
                                        {{ $permiso->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if ($permisosSinTipo->isNotEmpty())
            <div class="mb-4">
                <h6 class="text-secondary border-bottom pb-1 mb-2">
                    <span class="badge bg-secondary-transparent text-secondary">Sin tipo</span>
                </h6>
                <div class="row g-2 g-md-3">
                    @foreach ($permisosSinTipo as $permiso)
                        <div class="col-12 col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="{{ $permiso->id }}"
                                    id="permiso_{{ $permiso->id }}_role_{{ $item->id }}"
                                    {{ $item->hasPermissionTo($permiso->name) ? 'checked' : '' }}
                                    onchange="toggleUpdatePermission({{ $item->id }}, {{ $permiso->id }})">
                                <label class="form-check-label" for="permiso_{{ $permiso->id }}_role_{{ $item->id }}">
                                    {{ $permiso->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-4 pt-3 border-top">
            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="offcanvas">Cerrar</button>
        </div>
    </div>
</div>
