@extends('layouts.app')

@section('content')
<style>
    .authentication-background {
    background-color: rgb(199 201 203) !important;
    }
</style>
    <div class="authentication-background">
        <div class="container">
            <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
                <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                    <div class="card custom-card my-4">
                        <div class="card-body p-5">
                            <div class="col-xl-12" style="text-align: center">
                                {{-- <img src="{{ asset('assets/images/logonew.jpg') }}" style="max-height: 50px"> --}}
                            </div>
                            <div class="col-xl-12">&ensp;
                            </div>


                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <p class="mb-4 text-muted op-7 fw-normal text-center">Inicio de sesión</p>

                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <label for="signup-firstname" class="form-label text-default">Usuario<sup
                                                class="fs-12 text-danger">*</sup></label>

                                        <input id="username" type="text"
                                            class="form-control @error('username') is-invalid @enderror" name="username"
                                            value="{{ old('username') }}" required autocomplete="username" autofocus>

                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="signup-password" class="form-label text-default">Password<sup
                                                class="fs-12 text-danger">*</sup></label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="d-grid mt-4">
                                    <button class="btn btn-primary btn-lg">Acceder</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
