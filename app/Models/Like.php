<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_id', // Pastikan kolom ini benar di Model Like
    ];

    // Relasi: Like milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Like milik satu Photo
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}
