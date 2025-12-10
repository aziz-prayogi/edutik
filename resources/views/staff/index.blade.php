@extends('templates.app')

@section('content')
<div class="container my-5">
    <h1 class="text-primary mb-4">Panel Staff: Daftar Laporan (Pending)</h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Postingan Dilaporkan</th>
                    <th>Likes</th>
                    <th>Comments</th>
                    <th>Tipe Laporan</th>
                    <th>Alasan Detail</th>
                    <th>Pelapor</th>
                    <th>Waktu Lapor</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{-- Postingan Dilaporkan --}}
                        <div class="fw-bold">{{ $report->photo->title ?? 'Postingan telah dihapus' }}</div>
                        @if($report->photo)
                            {{-- Asumsi kolom foto adalah 'photo_url' --}}
                            <a href="{{ asset('storage/' . $report->photo->photo_url) }}" target="_blank" class="small text-muted">Lihat Postingan (ID: {{ $report->photo_id }})</a>
                        @else
                            <span class="text-danger small">Postingan fisik hilang.</span>
                        @endif
                    </td>
                    <td>
                        {{-- Jumlah Likes --}}
                        <span class="badge bg-info">{{ $report->photo->likes_count ?? '0' }}</span>
                    </td>
                    <td>
                        {{-- Jumlah Comments --}}
                        <span class="badge bg-info">{{ $report->photo->comments_count ?? '0' }}</span>
                    </td>
                    <td>
                        {{-- Tipe Laporan --}}
                        <span class="badge bg-danger">{{ strtoupper($report->type) }}</span>
                    </td>
                    <td>
                        {{-- Alasan Detail --}}
                        {{ $report->reason ?: '-' }}
                    </td>
                    <td>
                        {{-- Pelapor --}}
                        <div class="fw-bold">{{ $report->user->username ?? 'User Dihapus' }}</div>
                        <div class="small text-muted">{{ $report->user->email ?? '' }}</div>
                    </td>
                    <td>{{ $report->created_at->diffForHumans() }}</td>
                    <td>
                        {{-- Aksi --}}
                        <button class="btn btn-sm btn-success mb-1">Set Approved</button>
                        <button class="btn btn-sm btn-danger">Takedown Post</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">🎉 Tidak ada laporan yang sedang tertunda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection
