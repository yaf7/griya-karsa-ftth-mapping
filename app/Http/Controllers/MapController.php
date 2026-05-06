<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Line; // contoh model, ganti sesuai tabelmu
use App\Models\OpticalDistribution;

class MapController extends Controller
{
    public function view()
    {
        // ambil semua data line
        $lines = Line::all();
 $clients = Client::all();
$clients = Client::with('optical_distribution')->get();
$clients = Client::with('paket', 'optical_distribution')->get();
        // ambil semua data optical distribution (opsional kalau mau render icon ODP/ODC/Server juga)
        $optical_distribution = OpticalDistribution::with('kategori')->get();

        return view('map.view', [
            'mode' => 'view',
            'clients' => $clients,
            'lines' => Line::all(), // kalau mau nampilin semua garis
            'optical_distribution' => $optical_distribution
        ]);
    }
}
