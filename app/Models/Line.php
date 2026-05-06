<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Line extends Model
{
    use HasFactory;

    protected $table = 'line';
    protected $primaryKey = 'id_line';
    protected $fillable = ['id_optical_distribution', 'keterangan', 'kordinat'];

    public function opticalDistribution()
    {
        return $this->belongsTo(OpticalDistribution::class, 'id_optical_distribution');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'id_line');
    }
    // Line.php
public function optical_distribution()
{
    return $this->belongsTo(OpticalDistribution::class, 'id_optical_distribution', 'id_optical_distribution');
}

}
