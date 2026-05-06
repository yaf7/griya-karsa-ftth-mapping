<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';
    protected $primaryKey = 'id_paket';
    protected $fillable = ['nama_paket', 'kecepatan', 'harga'];

    public function clients()
    {
        return $this->hasMany(Client::class, 'id_paket');
    }
}
