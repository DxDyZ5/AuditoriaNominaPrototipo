@extends('layouts.auth')

@section('site_title', formatTitle([__('Login'), config('settings.title')]))

@section('head_content')

@endsection

@section('content')
<div class="d-flex align-items-center flex-fill py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 18px;">
                    <div class="row no-gutters">
                        <div class="col-12 col-lg-6 d-none d-lg-flex position-relative text-white" style="min-height: 520px; background-image: linear-gradient(135deg, rgba(16, 185, 129, 0.92), rgba(4, 120, 87, 0.92)), url({{ asset('img/login.svg') }}); background-size: cover; background-position: center;">
                            <div class="p-5 d-flex flex-column justify-content-center w-100">
                                <div class="text-uppercase font-weight-bold" style="letter-spacing: .08em; opacity: .9;">{{ __('Iniciar sesión') }}</div>
                                <div class="display-4 font-weight-bold mt-2 mb-0">{{ __('Recursos') }}<br>{{ __('Humanos') }}</div>
                                <div class="font-size-lg mt-3" style="max-width: 360px; opacity: .92;">{{ __('Accede al panel administrativo con tus credenciales.') }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="card-body p-4 p-lg-5">
                                <div class="text-center mb-4">
                                    <h1 class="h3 font-weight-bold mb-1">{{ __('Bienvenido de nuevo') }}</h1>
                                    <div class="text-muted">{{ __('Inicia sesión para continuar.') }}</div>
                                </div>

                                @include('shared.message')

                                @include('auth.partials.social')

                                <form method="POST" action="{{ route('login') }}" class="mt-3">
                                    @csrf

                                    <div class="form-group">
                                        <label for="i-email">{{ __('Correo electrónico') }}</label>
                                        <input id="i-email" type="text" dir="ltr" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="i-password">{{ __('Contraseña') }}</label>
                                        <input id="i-password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group d-flex justify-content-between align-items-center">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="remember" id="i-remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="i-remember">{{ __('Recordarme') }}</label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a class="text-success" href="{{ route('password.request') }}">{{ __('¿Olvidaste tu contraseña?') }}</a>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-block btn-success py-2">
                                        {{ __('Entrar') }}
                                    </button>
                                </form>

                                @if(config('settings.registration'))
                                    <div class="text-center text-muted mt-3">
                                        {{ __('¿No tienes una cuenta?') }}
                                        <a class="text-success font-weight-medium" href="{{ route('register') }}" role="button">{{ __('Crear una') }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
