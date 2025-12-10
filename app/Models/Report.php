<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_id', // KRITIS: Harus photo_id
        'type',     // Tipe laporan (misal: Spam, Kekerasan, dll.)
        'reason',   // Alasan detail dari user
        'status',   // Status laporan (pending, reviewed, rejected)
    ];

    // Relasi: Laporan dibuat oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Laporan terkait dengan satu Photo
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}
