<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // Definisikan semua kolom yang boleh diisi massal
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'thumbnail',
        'published_at',
    ];

    // Jika ingin tanggal dikenali sebagai instance Carbon
    protected $dates = ['published_at'];

    // Relasi opsional
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}