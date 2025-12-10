@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">

            <div class="col-md-8 col-lg-6">
                <h1 class="text-center mb-4 text-primary">For Your Pelajaran (FYP) - Foto</h1>

                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif

                {{-- 💡 Pengecekan Kosong Manual untuk Mengganti @forelse --}}
                @if ($photos->isEmpty())
                    <div class="alert alert-info text-center mt-5">
                        Belum ada foto pelajaran yang diunggah. Jadilah yang pertama!
                    </div>
                @else
                    {{-- 💡 LOOP MENGGUNAKAN @foreach --}}
                    @foreach ($photos as $photo)
                        <div class="card mb-4 shadow-sm border-0">

                            {{-- HEADER FOTO (USER INFO) --}}
                            <div class="card-header bg-white d-flex align-items-center">
                                <img src="{{ $photo->user?->profile_picture_url ?? 'https://via.placeholder.com/40' }}"
                                    alt="{{ $photo->user?->username }}" class="rounded-circle me-3"
                                    style="width: 40px; height: 40px;">
                                <div>
                                    <a href="#" class="fw-bold text-dark text-decoration-none">
                                        {{ '@' . $photo->user?->username }}
                                    </a>
                                    <p class="small text-muted mb-0">Diunggah: {{ $photo->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            {{-- KONTEN FOTO UTAMA --}}
                            <div class="card-body p-0">
                                <img src="{{ asset('storage/' . $photo->photo_url) }}" alt="{{ $photo->title }}"
                                    class="img-fluid w-100"
                                    style="max-height: 600px; width: 100%; object-fit: contain; display: block;">
                            </div>

                            {{-- DESKRIPSI DAN DETAIL --}}
                            <div class="p-3">
                                <h5 class="card-title">{{ $photo->title }}</h5>
                                <p class="card-text small text-muted">{{ $photo->description }}</p>
                                <span class="badge bg-secondary">{{ $photo->subject }}</span>
                            </div>

                            {{-- untuk mengetahui apakah user sudah pernah ngelike post ini --}}
                            @php
                                $isLiked = $photo->likes->contains('user_id', Auth::id());
                                $likeClass = $isLiked ? 'btn-danger' : 'btn-outline-danger';
                            @endphp

                            {{-- FOOTER AKSI (Like/Report BUTTONS) --}}
                            <div class="card-footer bg-white d-flex justify-content-between">
                                {{-- Tombol Like --}}
                                <form action="{{ route('user.like', $photo->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $likeClass }}">
                                        <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart me-1"></i>
                                        {{ $isLiked ? 'Disukai' : 'Suka' }} ({{ $photo->likes->count() }})
                                    </button>
                                </form>

                                {{-- Tombol Lapor (Membuka Modal) --}}
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                    data-bs-target="#reportModal_{{ $photo->id }}">
                                    <i class="fas fa-flag"></i> Lapor
                                </button>
                            </div>

                            <div class="p-3 border-top bg-light">
                                {{-- Judul Jumlah Komentar --}}
                                <h6 class="small text-muted mb-2">Komentar Terbaru ({{ $photo->comments->count() }})</h6>

                                {{-- 1. TAMPILKAN 2 KOMENTAR PERTAMA (SELALU MUNCUL) --}}
                                @foreach ($photo->comments->take(2) as $comment)
                                    <div class="d-flex mb-1">
                                        <small class="fw-bold me-2">{{ $comment->user?->username }}:</small>
                                        <small class="text-break">{{ $comment->content }}</small>
                                    </div>
                                @endforeach

                                {{-- 2. TAMPILKAN SISA KOMENTAR (DISEMBUNYIKAN/COLLAPSE) --}}
                                @if ($photo->comments->count() > 2)
                                    <div class="collapse" id="collapseComments{{ $photo->id }}">
                                        @foreach ($photo->comments->skip(2) as $comment)
                                            <div class="d-flex mb-1">
                                                <small class="fw-bold me-2">{{ $comment->user?->username }}:</small>
                                                <small class="text-break">{{ $comment->content }}</small>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- TOMBOL UNTUK MEMBUKA/MENUTUP --}}
                                    <button class="btn btn-link btn-sm p-0 text-decoration-none small" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseComments{{ $photo->id }}"
                                        aria-expanded="false" aria-controls="collapseComments{{ $photo->id }}">
                                        Lihat {{ $photo->comments->count() - 2 }} komentar lainnya...
                                    </button>
                                @endif

                                {{-- Jika tidak ada komentar sama sekali --}}
                                @if ($photo->comments->count() == 0)
                                    <small class="text-muted d-block mb-2">Belum ada komentar.</small>
                                @endif

                                <hr class="my-2">

                                {{-- FORM INPUT KOMENTAR --}}
                                <form action="{{ route('user.comment.store', $photo->id) }}" method="POST" class="d-flex">
                                    @csrf
                                    <input type="text" name="content" placeholder="Tulis komentar..."
                                        class="form-control form-control-sm me-2 @error('content') is-invalid @enderror"
                                        required>
                                    <button type="submit" class="btn btn-primary btn-sm">Kirim</button>

                                    @error('content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </form>
                            </div>


                            {{-- 💡 MODAL LAPORAN (INI HARUS DI DALAM LOOP) --}}
                            <div class="modal fade" id="reportModal_{{ $photo->id }}" tabindex="-1"
                                aria-labelledby="reportModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('user.report.store', $photo->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="reportModalLabel">Laporkan Foto
                                                    "{{ $photo->title }}"</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Tipe Pelanggaran</label>
                                                    <select name="type"
                                                        class="form-select @error('type') is-invalid @enderror" required>
                                                        <option value="" selected disabled>Pilih salah satu...
                                                        </option>
                                                        <option value="spam">Spam atau Iklan</option>
                                                        <option value="explicit">Konten Eksplisit</option>
                                                        <option value="misinformation">Informasi Salah</option>
                                                        <option value="other">Lainnya</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan Detail (Opsional)</label>
                                                    <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3"></textarea>
                                                    @error('reason')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Kirim Laporan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- Akhir Modal Report --}}


                        </div> {{-- Penutup card mb-4 shadow-sm border-0 --}}
                    @endforeach
                @endif {{-- Penutup @if ($photos->isEmpty()) --}}

                {{-- Tambahkan link Paginate (pastikan $photos sudah dipaginate di Controller) --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $photos->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
