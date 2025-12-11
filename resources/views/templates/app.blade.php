<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edutic - For Your Pelajaran</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            @if(Auth::check())
            <a class="navbar-brand me-2 fw-bold text-primary">
                <i class="fas fa-graduation-cap me-1"></i> EDUTIC
            </a>
            @else
            <a class="navbar-brand me-2 fw-bold text-primary" href="{{ route('home') }}">
                <i class="fas fa-graduation-cap me-1"></i> EDUTIC
            </a>
            @endif

            <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarButtonsExample"
                aria-controls="navbarButtonsExample" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-danger fw-bold" href="#">
                                <i class="fas fa-shield-alt me-1"></i> Dashboard Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users me-1"></i> User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-exclamation-triangle me-1"></i> Report
                            </a>
                        </li>
                    @elseif (Auth::check()  && Auth::user()->role == 'staff')
                        <li class="nav-item">
                             <a class="nav-link text-danger fw-bold">
                                <i class="fas fa-shield-alt me-1"></i> Dashboard staff
                             </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.index') }}">
                                <i class="fas fa-home me-1"></i> FYP
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-search me-1"></i> Cari Pelajaran
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="d-flex align-items-center">
                    @if (Auth::check() && (Auth::user()->role == 'staff' || Auth::user()->role == 'admin'))
                        <a href="#" type="button" class="btn btn-link px-3 me-2">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->username }}
                        </a>

                        {{-- Tombol Logout --}}
                        <a href="{{ route('logout') }}" class="btn btn-danger">
                            Logout
                        </a>
                    @elseif(Auth::check())
                        {{-- Tombol Upload Video (Tersedia untuk SEMUA pengguna yang login) --}}
                        <a href="{{ route('user.create') }}" class="btn btn-warning me-3">
                            <i class="fas fa-upload me-1"></i> Upload
                        </a>

                        {{-- Tombol Profil Pengguna --}}
                        {{-- {{ route(, Auth::user()->username) }} --}}
                        <a href="{{ route('user.profile.edit') }}"
                            class="btn btn-link px-3 me-2 text-decoration-none text-dark d-flex align-items-center">
                            {{-- Panggil Accessor yang kita buat di Model --}}
                            <img src="{{ Auth::user()->profile_picture_url }}" class="rounded-circle me-2"
                                style="width: 30px; height: 30px; object-fit: cover;">

                            {{ Auth::user()->username }}
                        </a>

                        {{-- Tombol Logout --}}
                        <a href="{{ route('logout') }}" class="btn btn-danger">
                            Logout
                        </a>
                    @else
                        {{-- Tombol Login / Sign Up (Jika Belum Login) --}}
                        <a href="{{ route('login') }}" type="button" class="btn btn-link px-3 me-2">
                            Login
                        </a>
                        <a href="{{ route('signup') }}" type="button" class="btn btn-primary me-3">
                            Daftar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    @yield('content')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>


    @stack('script')
</body>

</html>
