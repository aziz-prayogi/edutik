<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reports = Report::with([
                'user:id,username,email',
                'photo' => function ($query) {
                    // Eager load likes dan comments count pada setiap photo
                    $query->withCount(['likes', 'comments']);
                    $query->withTrashed(); // Memungkinkan melihat foto yang sudah di-Soft Delete
                }
            ])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Menggunakan view yang sama (staff.reports)
        return view('staff.reports', compact('reports'));
    }

    public function deletePost(Photo $photo)
    {
        try {
            // 1. Hapus file fisik dari storage jika ada
            if ($photo->photo_url && Storage::disk('public')->exists($photo->photo_url)) {
                Storage::disk('public')->delete($photo->photo_url);
            }

            // 2. Hapus data dari database (Soft Delete atau Force Delete tergantung model)
            // Karena relasi cascade, laporan terkait biasanya ikut terhapus atau tetap ada tapi null
            $photo->forceDelete(); // Gunakan forceDelete jika ingin benar-benar hilang

            return redirect()->back()->with('success', 'Postingan berhasil dihapus dari sistem.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus postingan: ' . $e->getMessage());
        }
    }

    /**
     * Meninjau laporan (Tolak Laporan / Tandai Selesai).
     * Route: PATCH /staff/reports/{report}/review
     */
    public function review(Request $request, Report $report)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:reviewed,rejected',
        ]);

        // Update status laporan
        $report->update([
            'status' => $request->status
        ]);

        $message = $request->status == 'rejected'
            ? 'Laporan ditolak. Postingan tetap aman.'
            : 'Laporan ditandai telah ditinjau.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
