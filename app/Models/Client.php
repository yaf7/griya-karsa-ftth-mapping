<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'id_client';

    // Field yang boleh diisi (mass assignment)
    protected $fillable = [
        'kode',
        'nomor',
        'alamat',
        'nama',
        'kordinat',
        'foto',
        'id_optical_distribution',
        'user_pppoe',
        'tanggal_regis',
        'tanggal_pembayaran',
        'id_paket',
        'status',
    ];

    // Kalau tanggal ingin otomatis diperlakukan sebagai Carbon
    protected $dates = [
        'tanggal_regis',
        'tanggal_pembayaran',
        'created_at',
        'updated_at',
    ];

    // Relasi ke paket (jika tabel paket ada)
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket', 'id_paket');
    }


    // Relasi ke optical distribution (jika tabelnya ada)
public function optical_distribution()
{
    return $this->belongsTo(OpticalDistribution::class, 'id_optical_distribution', 'id_optical_distribution');
}


}
