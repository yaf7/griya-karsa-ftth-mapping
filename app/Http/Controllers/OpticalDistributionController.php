<?php

namespace App\Http\Controllers;

use App\Models\OpticalDistribution;
use App\Models\Kategori;
use App\Models\Client;
use Illuminate\Http\Request;

use App\Models\Line;
class OpticalDistributionController extends Controller
{
    /**
     * Tampilkan daftar optical distribution
     */
    public function index()
    {
        $data = OpticalDistribution::with('kategori')->latest()->paginate(10);
        return view('optical_distribution.index', compact('data'));
    }

    /**
     * Tampilkan form create
     */public function create(Request $request)
     
{
    $lines = Line::with('optical_distribution.kategori')->get();
    $kategori = Kategori::all();

    $clients = Client::with('paket', 'optical_distribution')->get();
    // cek apakah ada parameter pencarian
    $search = $request->input('q');
    $optical_distribution = OpticalDistribution::with('kategori')
        ->when($search, function ($query, $search) {
            $query->where('kode', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhere('keterangan', 'like', "%{$search}%");
        })
        ->get();

    return view('optical_distribution.create', [
        'optical_distribution' => $optical_distribution,
        'mode' => 'create_point',
        'lines' => $lines,
        'kategori' => $kategori,
        'search' => $search,
        'clients' => $clients
    ]);
}




    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:optical_distribution,kode',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'inputan' => 'nullable|string',
            'kordinat' => 'required|string',
            'estimasi_redaman_input' => 'nullable|numeric',
            'estimasi_redaman_output' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('optical', 'public');
        }

        OpticalDistribution::create($validated);

        return redirect()->route('optical_distribution.index')
                         ->with('success', 'Data Optical Distribution berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail
     */
    public function show($id)
    {
        $item = OpticalDistribution::with('kategori')->findOrFail($id);
        return view('optical_distribution.show', compact('item'));
    }

    /**
     * Tampilkan form edit
     */

public function edit($id)
{
    $clients = Client::with('paket', 'optical_distribution')->get();
    $item = OpticalDistribution::findOrFail($id);
    $kategori = Kategori::all();

    // tentukan mode
    $mode = 'edit_point';
    if ($item->kategori && strtolower($item->kategori->nama) === 'line') {
        $mode = 'edit_line';
    }

    // ambil semua data line untuk ditampilkan di peta
    $lines = Line::with('optical_distribution.kategori')->get();

    // ambil semua titik untuk marker ODP/ODC/Server
    $optical_distribution = OpticalDistribution::with('kategori')->get();

    return view('optical_distribution.edit', compact('item', 'kategori', 'clients', 'mode', 'lines', 'optical_distribution'));
}

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $item = OpticalDistribution::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|unique:optical_distribution,kode,' . $id . ',id_optical_distribution',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'inputan' => 'nullable|string',
            'kordinat' => 'required|string',
            'estimasi_redaman_input' => 'nullable|numeric',
            'estimasi_redaman_output' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('optical', 'public');
        }

        $item->update($validated);

        return redirect()->route('optical_distribution.index')
                         ->with('success', 'Data Optical Distribution berhasil diperbarui!');
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        $item = OpticalDistribution::findOrFail($id);
        $item->delete();

        return redirect()->route('optical_distribution.index')
                         ->with('success', 'Data Optical Distribution berhasil dihapus!');
    }
}
