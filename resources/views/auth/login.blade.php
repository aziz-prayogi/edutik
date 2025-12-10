@extends('templates.app')

@section('content')
 @if (Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
        @if (Session::get('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-5-strong">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4 text-center text-primary">Masuk ke Edutic</h2>
                    <p class="text-center text-muted mb-4">Mulai belajar dengan video pendek terbaik!</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Input Username/Email --}}
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus />
                            <label class="form-label" for="email">Email</label>
                            @error('loginId')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Input Password --}}
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required />
                            <label class="form-label" for="password">Kata Sandi</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Remember Me & Lupa Sandi --}}
                        <div class="row mb-4">
                            <div class="col d-flex justify-content-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember"> Ingat Saya </label>
                                </div>
                            </div>

                        </div>

                        {{-- Tombol Login --}}
                        <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block mb-3">
                            Masuk
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
