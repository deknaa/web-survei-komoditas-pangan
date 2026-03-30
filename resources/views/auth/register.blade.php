<x-guest-layout>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header text-center bg-primary text-white py-3">
                        <h4 class="mb-0 font-weight-bold">{{ __('Daftar Akun Petugas') }}</h4>
                    </div>
                    <div class="card-body p-4">

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">{{ __('Nama') }}</label>
                                <input id="name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Masukkan nama lengkap"
                                       class="form-control form-control-lg"
                                       required
                                       autofocus
                                       autocomplete="name">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

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
                                       autocomplete="new-password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
                                <input id="password_confirmation"
                                       type="password"
                                       name="password_confirmation"
                                       placeholder="Ulangi password"
                                       class="form-control form-control-lg"
                                       required
                                       autocomplete="new-password">
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-md px-4 w-full">
                                    {{ __('Sudah Punya Akun?') }}
                                </a>
                                <button type="submit" class="btn btn-primary btn-md px-4 w-full">
                                    {{ __('Daftar') }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>