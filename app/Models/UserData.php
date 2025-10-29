<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $table = 'user_data';
    protected $primaryKey = 'data_user_id';
    protected $fillable = ['user_id', 'nama', 'alamat', 'telepon'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'customer_id');
    }
}