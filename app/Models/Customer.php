<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = ['user_id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'customer_id');
    }
}
