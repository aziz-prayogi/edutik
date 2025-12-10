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
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-5-strong">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4 text-center text-primary">Buat Akun Edutic</h2>
                    <p class="text-center text-muted mb-4">Bergabunglah dan mulai bagikan pelajaran Anda!</p>

                    <form method="POST" action="{{ route('signup') }}">
                        @csrf

                        {{-- Input Username --}}
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus />
                            <label class="form-label" for="username">Username</label>
                            @error('username')
                                <span class="invalid-feedback" >
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Input Email --}}
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" />
                            <label class="form-label" for="email">Alamat Email</label>
                            @error('email')
                                <span class="invalid-feedback" >
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Input Password --}}
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" />
                            <label class="form-label" for="password">Kata Sandi</label>
                            @error('password')
                                <span class="invalid-feedback" >
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        {{-- Tombol Daftar --}}
                        <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block mb-3">
                            Daftar Akun
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
