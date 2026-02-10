@extends('menu')
@section('content')
    <div class="row">
        <div class="col-12">
            <a href="{{ route('user.index') }}" class="btn btn-outline-secondary btn-sm mb-2"><i class="ri-arrow-left-line"></i> Volver al listado</a>
        </div>
    </div>
    @include('seguridad.usuario.drawer-edit', ['item' => $usuario])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('drawer-edit-{{ $usuario->id }}');
            if (el) {
                var drawer = new bootstrap.Offcanvas(el);
                drawer.show();
            }
            expandMenuAndHighlightOption('seguridadMenu', 'usuarioOption');
        });
    </script>
@endsection
