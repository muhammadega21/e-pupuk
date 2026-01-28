<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PupukGambar extends Model
{
    protected $table = 'pupuk_gambar';
    protected $primaryKey = 'pupuk_gambar_id';
    protected $fillable = ['pupuk_id', 'gambar_url'];
    public function pupuk()
    {
        return $this->belongsTo(Pupuk::class, 'pupuk_id');
    }
}
