@extends('templates.app')
@section('content')
    @if (Session::get('success'))
        {{-- Auth::user()->field : mengambil data orang yang login, field dari fillable model user --}}
        <div class="alert alert-success">
            {{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->username }}</b>
        </div>
    @endif
    @if (Session::get('logout'))
        <div class="alert alert-warning">
            {{ Session::get('logout') }}
        </div>
    @endif
    <div class="container text-center py-5">

        <header class="mb-5">
            <h1 class="display-3 fw-bolder text-primary">Selamat Datang di Edutic!</h1>
            <p class="lead text-muted">Platform Belajar Singkat Terbaik.</p>
        </header>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <p>
                    Edutic menyediakan pelajaran yang siap menemani, dibuat oleh para pendidik terbaik. Mulai
                    perjalanan belajar Anda hari ini!
                </p>
                <div class="mt-4">
                    <a href="{{ route('signup') }}" class="btn btn-primary btn-lg shadow-sm me-3">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i> Sudah Punya Akun? Login
                    </a>
                </div>
            </div>
        </div>
        <hr>
        <section class="mt-5">
            <h2 class="h4 text-secondary mb-4">Fitur Utama</h2>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <i class="fas fa-graduation-cap fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Pelajaran Singkat</h5>
                    <p>Belajar materi kompleks dalam sebuah post.</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-share-alt fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold">Berbagi Ilmu</h5>
                    <p>Upload post Anda sendiri dan bantu komunitas.</p>
                </div>
            </div>
        </section>
    </div>
@endsection
