<div class="modal fade" id="modal-edit-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Modificar permiso</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('permission.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">


                    <div class="row gy-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="edit_name_{{ $item->id }}" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="name" id="edit_name_{{ $item->id }}"
                                value="{{ old('name', $item->name) }}" required>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="edit_permission_type_id_{{ $item->id }}" class="form-label">Tipo de permiso</label>
                            <select name="permission_type_id" id="edit_permission_type_id_{{ $item->id }}" class="form-select">
                                <option value="">— Sin tipo —</option>
                                @foreach ($permissionTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('permission_type_id', $item->permission_type_id) == $type->id)>
                                        {{ $type->name }}
                                    </option>
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

        </form>
    </div>
</div>
