@extends('templates.app')
{{-- Pastikan ini mengarah ke layout Anda yang benar (templates.app) --}}

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            {{-- Menampilkan pesan error atau sukses --}}
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="text-center text-primary fw-bold mb-4">
                        <i class="fas fa-camera me-2"></i> Unggah Foto Pelajaran Baru
                    </h3>

                    {{-- FORM UPLOAD FOTO --}}
                    {{-- WAJIB: method="POST" dan enctype="multipart/form-data" --}}
                    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- 1. Input Judul (title) --}}
                        <div class="mb-4">
                            <label class="form-label" for="title">Judul Foto</label>
                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required />
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- 2. Input Deskripsi (description) --}}
                        <div class="mb-4">
                            <label class="form-label" for="description">Deskripsi Singkat</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- 3. Pilihan Mata Pelajaran (subject) --}}
                        <div class="mb-4">
                            <label class="form-label small text-muted">Mata Pelajaran</label>
                            <select class="form-select @error('subject') is-invalid @enderror" name="subject" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Matematika" {{ old('subject') == 'Matematika' ? 'selected' : '' }}>Matematika</option>
                                <option value="Fisika" {{ old('subject') == 'Fisika' ? 'selected' : '' }}>Fisika</option>
                                <option value="Biologi" {{ old('subject') == 'Biologi' ? 'selected' : '' }}>Biologi</option>
                                <option value="Sejarah" {{ old('subject') == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                                <option value="Bahasa Inggris" {{ old('subject') == 'Bahasa Inggris' ? 'selected' : '' }}>Bahasa Inggris</option>
                                <option value="Seni" {{ old('subject') == 'Seni' ? 'selected' : '' }}>Seni Budaya</option>
                                <option value="Komputer" {{ old('subject') == 'Komputer' ? 'selected' : '' }}>TIK / Komputer</option>
                            </select>
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- 4. Upload File Foto (photo_file) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold @error('photo_file') text-danger @enderror" for="photo_file">
                                File Foto (Max 5MB)
                            </label>
                            {{-- NAME HARUS 'photo_file' --}}
                            <input type="file" class="form-control @error('photo_file') is-invalid @enderror"
                                id="photo_file" name="photo_file"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp" required />
                            @error('photo_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-3">
                            <i class="fas fa-cloud-upload-alt me-2"></i> Unggah Foto
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('user.index') }}" class="text-muted">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
