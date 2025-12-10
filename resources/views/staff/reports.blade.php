@extends('templates.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary"><i class="fas fa-clipboard-list me-2"></i>Staff Panel: Laporan Masuk</h1>
        <span class="badge bg-warning text-dark">Pending Review: {{ $reports->total() }}</span>
    </div>

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th style="width: 25%;">Postingan (Foto)</th>
                            <th>Statistik</th>
                            <th>Detail Laporan</th>
                            <th>Pelapor</th>
                            <th>Waktu</th>
                            <th style="width: 15%;">Aksi Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
                            <td>
                                @if($report->photo)
                                    <div class="d-flex align-items-center">
                                        {{-- Thumbnail Image --}}
                                        <img src="{{ asset('storage/' . $report->photo->photo_url) }}"
                                             class="rounded me-2"
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             data-bs-toggle="modal"
                                             data-bs-target="#previewModal{{ $report->id }}"
                                             role="button">

                                        <div>
                                            <div class="fw-bold text-dark">{{ Str::limit($report->photo->title, 30) }}</div>
                                            <small class="text-muted">{{ $report->photo->subject }}</small>
                                            <br>
                                            {{-- Link to view modal --}}
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#previewModal{{ $report->id }}" class="small text-primary">
                                                Lihat Full
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-danger">Postingan sudah dihapus</span>
                                @endif
                            </td>
                            <td>
                                @if($report->photo)
                                    <span class="badge bg-danger mb-1"><i class="fas fa-heart me-1"></i> {{ $report->photo->likes_count }}</span><br>
                                    <span class="badge bg-info"><i class="fas fa-comment me-1"></i> {{ $report->photo->comments_count }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark mb-1">{{ strtoupper($report->type) }}</span>
                                <p class="small mb-0 text-muted fst-italic">"{{ Str::limit($report->reason, 50) ?: 'Tidak ada alasan detail' }}"</p>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $report->user->username ?? 'Unknown' }}</div>
                                <small class="text-muted">{{ $report->user->email ?? '' }}</small>
                            </td>
                            <td>
                                <small>{{ $report->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="d-grid gap-2">
                                    {{-- Tombol Hapus Postingan --}}
                                    @if($report->photo)
                                    <form action="{{ route('staff.reports.deletePost', $report->photo->id) }}" method="POST" onsubmit="return confirm('Yakin hapus postingan ini? Tindakan tidak bisa dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus Post
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Tombol Tolak Laporan --}}
                                    <form action="{{ route('staff.reports.review', $report->id) }}" method="POST" onsubmit="return confirm('Tolak laporan ini? Postingan akan tetap ada.');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-check me-1"></i> Tolak Laporan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL PREVIEW FOTO --}}
                        @if($report->photo)
                        <div class="modal fade" id="previewModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Bukti Laporan: {{ $report->photo->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center bg-dark">
                                        <img src="{{ asset('storage/' . $report->photo->photo_url) }}" class="img-fluid" style="max-height: 80vh;">
                                    </div>
                                    <div class="modal-footer justify-content-start">
                                        <div>
                                            <strong>Alasan Full:</strong>
                                            <p class="mb-0">{{ $report->reason ?: 'Tidak ada' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                <h5>Tidak ada laporan pending.</h5>
                                <p>Kerja bagus! Semua aman terkendali.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
