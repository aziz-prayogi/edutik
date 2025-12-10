<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //
    use HasFactory, SoftDeletes; // Pastikan SoftDeletes ada

    protected $fillable = [
        'user_id',
        'photo_id', // KRITIS: Harus photo_id
        'content',
    ];

    // Relasi: Komentar dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Komentar milik satu Photo
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}
