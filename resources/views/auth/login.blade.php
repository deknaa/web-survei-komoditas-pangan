<x-guest-layout>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-5"> {{-- Lebar diperbesar --}}
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header text-center bg-primary text-white py-3">
                        <h4 class="mb-0 font-weight-bold">{{ __('Login') }}</h4>
                    </div>
                    <div class="card-body p-4">

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-3" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Masukkan email"
                                       class="form-control form-control-lg"
                                       required
                                       autofocus
                                       autocomplete="username">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password"
                                       type="password"
                                       name="password"
                                       placeholder="Masukkan password"
                                       class="form-control form-control-lg"
                                       required
                                       autocomplete="current-password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <label for="remember_me" class="form-check-label">
                                    {{ __('Remember me') }}
                                </label>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                {{-- @if (Route::has('password.request'))
                                    <a class="small text-primary" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif --}}

                                <button type="submit" class="btn btn-primary btn-md px-4 w-full">
                                    {{ __('Log in') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- <div class="card-footer text-center small bg-light py-3">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" class="text-primary font-weight-bold">
                            {{ __('Register') }}
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
