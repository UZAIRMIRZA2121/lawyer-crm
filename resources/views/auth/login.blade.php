@extends('layouts.frontend.master')

@section('main')
<section class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <h2 class="text-center mb-4">{{ __('Login') }}</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="current-password">

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <!-- Submit + Forgot Password -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Login') }}
                    </button>
{{-- 
                    @if (Route::has('password.request'))
                        <a class="btn btn-link text-center" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif --}}
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
