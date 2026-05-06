<?php

// app/Models/OpticalDistribution.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpticalDistribution extends Model
{
    use HasFactory;

    protected $table = 'optical_distribution';
    protected $primaryKey = 'id_optical_distribution';
    protected $fillable = [
        'kode', 'id_kategori', 'inputan', 'kordinat',
        'estimasi_redaman_input', 'estimasi_redaman_output',
        'foto', 'keterangan'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
