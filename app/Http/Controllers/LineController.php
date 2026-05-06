<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\OpticalDistribution; 
use App\Models\Client;
use Illuminate\Http\Request;

class LineController extends Controller
{
    public function index()
    {
        $lines = Line::with('opticalDistribution.kategori')->latest()->paginate(10);
        return view('line.index', compact('lines'));
    }
public function create(Request $request)
{
    $search = $request->q;
    $clients = Client::all();
$clients = Client::with('optical_distribution')->get();
$clients = Client::with('paket', 'optical_distribution')->get();

    $lines = Line::with('opticalDistribution.kategori')
        ->when($search, function ($query, $search) {
            $query->where('id_line', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('opticalDistribution', function ($q) use ($search) {
                      $q->where('kode', 'like', "%{$search}%")
                        ->orWhereHas('kategori', function ($q2) use ($search) {
                            $q2->where('nama', 'like', "%{$search}%");
                        });
                  });
        })
        ->orderBy('created_at', 'desc')
        ->get();

    $optical_distribution = OpticalDistribution::with('kategori')->get();

    return view('line.create', [
        'optical_distribution' => $optical_distribution,
        'mode' => 'create_line',
        'lines' => $lines,
        'clients' => $clients
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'id_optical_distribution' => 'required|unique:line,id_optical_distribution',
            'keterangan' => 'nullable|string',
            'kordinat' => 'required|string',
        ]);
        
        Line::create($request->all());
        
        return redirect()->route('line.index')->with('success', 'Line berhasil ditambahkan.');
    }
    
    public function edit(Line $line)
    {
         $clients = Client::all();
$clients = Client::with('optical_distribution')->get();
$clients = Client::with('paket', 'optical_distribution')->get();
        $optical_distribution = OpticalDistribution::with('kategori')->get();
        $lines = Line::with('optical_distribution.kategori')->get();

    return view('line.edit', [
        'line' => $line,
        'optical_distribution' => $optical_distribution,
        'mode' => 'edit_line',
        'lines' => $lines,
        'clients' => $clients,
        'kordinat' => $line->kordinat
    ]);
}


    public function update(Request $request, Line $line)
    {
        $request->validate([
            'id_optical_distribution' => 'required|unique:line,id_optical_distribution,' . $line->id_line . ',id_line',
            'keterangan' => 'nullable|string',
            'kordinat' => 'required|string',
        ]);

        $line->update($request->all());

        return redirect()->route('line.index')->with('success', 'Line berhasil diperbarui.');
    }

    public function destroy(Line $line)
    {
        $line->delete();
        return redirect()->route('line.index')->with('success', 'Line berhasil dihapus.');
    }
}
