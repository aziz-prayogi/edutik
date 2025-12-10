@extends('templates.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Edit Profil</h5>
                </div>
                <div class="card-body">
                    {{-- Tampilkan Pesan Sukses --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Preview Foto Saat Ini --}}
                        <div class="text-center mb-4">
                            <img src="{{ $user->profile_picture_url }}"
                                 alt="Profile Picture"
                                 class="rounded-circle shadow-sm"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <p class="small text-muted mt-2">Foto saat ini</p>
                        </div>

                        {{-- Input Username --}}
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                        </div>

                        {{-- Input Upload Foto --}}
                        <div class="mb-3">
                            <label class="form-label">Ganti Foto Profil</label>
                            <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror">
                            <div class="form-text">Format: JPG, PNG. Maksimal 2MB.</div>
                            @error('profile_picture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
