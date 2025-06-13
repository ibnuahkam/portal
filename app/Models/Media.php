<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    // Kolom yang boleh diisi massal
    protected $fillable = [
        'article_id',
        'type',
        'images',
    ];

    // Relasi ke artikel
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}