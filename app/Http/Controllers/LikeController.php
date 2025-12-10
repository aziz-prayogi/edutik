<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function like(Request $request, Photo $photo)
    {
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

    // Redirect kembali ke halaman sebelumnya (FYP)
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
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        //
    }
}
