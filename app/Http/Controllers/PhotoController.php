<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Models\Photo;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Report;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $photos = Photo::with('user')->latest()->paginate(10);
        return view('user.index', compact('photos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('user.create');
    }

    public function like(Request $request, Photo $photo)
    {
        // 1. Cek apakah user sudah pernah like foto ini
        $existingLike = Like::where('user_id', Auth::id())
                            ->where('photo_id', $photo->id)
                            ->first();

        if ($existingLike) {
            // User sudah pernah like -> HAPUS LIKE (UNLIKE)
            $existingLike->delete();
            $message = 'Unlike berhasil!';
        } else {
            // User belum like -> TAMBAH LIKE
            Like::create([
                'user_id' => Auth::id(),
                'photo_id' => $photo->id,
            ]);
            $message = 'Like berhasil!';
        }

        // 2. Redirect kembali ke halaman sebelumnya (FYP)
        return redirect()->back()->with('success', $message);
    }

    public function commentStore(Request $request, Photo $photo)
    {
        // 1. Validasi Input
        $request->validate([
            'content' => 'required|string|max:500', // Konten komentar wajib diisi
        ], [
            'content.required' => 'Komentar tidak boleh kosong.',
            'content.max' => 'Komentar maksimal 500 karakter.',
        ]);

        try {
            // 2. Simpan Komentar
            Comment::create([
                'user_id' => Auth::id(),
                'photo_id' => $photo->id, // Menggunakan ID Foto dari URL
                'content' => $request->content,
            ]);

            // 3. Redirect kembali
            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Error handling
            // dd($e); // Hapus ini setelah debugging
            return redirect()->back()->with('error', 'Gagal menyimpan komentar. Coba lagi.')->withInput();
        }
    }

    public function reportStore(Request $request, Photo $photo)
    {
        // 1. Validasi Input
        $request->validate([
            'type' => 'required|in:spam,explicit,misinformation,other', // Tipe laporan harus salah satu dari ini
            'reason' => 'nullable|string|max:1000', // Alasan detail (opsional)
        ], [
            'type.required' => 'Anda harus memilih tipe laporan.',
            'type.in' => 'Tipe laporan yang dipilih tidak valid.',
        ]);

        try {
            // 2. Cek Duplikasi
            $existingReport = Report::where('user_id', Auth::id())
                                    ->where('photo_id', $photo->id)
                                    ->first();

            if ($existingReport) {
                return redirect()->back()->with('error', 'Anda sudah pernah melaporkan foto ini.');
            }

            // 3. Simpan Laporan
            Report::create([
                'user_id' => Auth::id(),
                'photo_id' => $photo->id,
                'type' => $request->type,
                'reason' => $request->reason,
                'status' => 'pending', // Default status saat laporan masuk
            ]);

            // 4. Redirect kembali
            return redirect()->back()->with('success', 'Laporan berhasil dikirim! Terima kasih atas kontribusi Anda.');

        } catch (\Exception $e) {
            // Error handling
            // dd($e); // Hapus ini setelah debugging
            return redirect()->back()->with('error', 'Gagal menyimpan laporan. Coba lagi.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Diadaptasi dari validasi Poster Film)
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|min:10|max:1000', // Diubah menjadi required min:10
            'subject' => 'required|string',

            // Aturan untuk Foto (Pengganti 'poster' film)
            'photo_file' => [
                'nullable', // Kita akan cek required secara manual
                'file',
                'image',
                'mimes:jpeg,png,jpg,gif,svg,webp',
                'max:5120' // Max 5MB
            ],
        ], [
            'title.required' => 'Judul foto harus diisi.',
            'description.required' => 'Deskripsi/Sinopsis harus diisi.',
            'description.min' => 'Deskripsi diisi minimal 10 karakter.',
            'subject.required' => 'Mata pelajaran (kategori) harus diisi.',
            'photo_file.required' => 'File foto wajib diunggah.',
            'photo_file.mimes' => 'Format foto harus jpg, jpeg, png, gif, svg, atau webp.',
            'photo_file.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        // 2. Akses File (Menggunakan Fallback untuk mengatasi Bug Parsing)
        $photoFile = $request->file('photo_file');
        $photoPath = null;

        // 🚨 FALLBACK KRITIS: Mengatasi kegagalan $request->file() di lingkungan tertentu
        if (!$photoFile && isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
            $fileData = $_FILES['photo_file'];
            $photoFile = new UploadedFile(
                $fileData['tmp_name'],
                $fileData['name'],
                $fileData['type'] ?? 'application/octet-stream',
                $fileData['error'],
                $fileData['size'],
                true
            );
        }

        // 3. Pengecekan Kualitas File dan Keberadaan (Required Manual)
        if (!$photoFile || !$photoFile->isValid()) {
            // Jika file tidak ditemukan atau tidak valid, lemparkan error manual
            return redirect()->back()->withErrors(['photo_file' => 'File foto wajib diunggah dan harus valid.'])->withInput();
        }

        // 4. Proses Upload dan Penyimpanan
        try {
            // Logika Penyimpanan File (Diadaptasi dari storeAs)
            // Namun, kita gunakan store() saja untuk nama unik otomatis
            // Disimpan di public/storage/photos/
            $photoPath = $photoFile->store('photos', 'public');

            // 5. Simpan ke Database (Menggunakan Model Photo)
            Photo::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'subject' => $request->subject,
                // Yang disimpan adalah PATH (lokasi file)
                'photo_url' => $photoPath,
            ]);

            // 6. Redirect Sukses
            return redirect()->route('user.index')->with('success', 'Foto pelajaran berhasil diunggah!');

        } catch (\Exception $e) {
            // 7. Error Handling & Cleanup
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            // dd($e); // Hapus ini jika sudah tidak dibutuhkan
            return redirect()->back()->with('error', 'Gagal menyimpan data ke database. Silakan coba lagi.')->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        //
    }
}
