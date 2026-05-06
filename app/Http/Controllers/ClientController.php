<?php

namespace App\Http\Controllers;
use App\Models\Line; 
use App\Models\Client;
use App\Models\Paket;
use App\Models\OpticalDistribution;

use Illuminate\Http\Request;
use App\Models\Kategori;
class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('client.index', compact('clients'));
    }
    public function create(Request $request)
    {
        $lines = Line::with('optical_distribution.kategori')->get();
        $kategori = Kategori::all();
        $clients = Client::with(['paket', 'optical_distribution'])->get();
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

        $pakets = Paket::all();

        return view('client.create', compact(
            'lines',
            'kategori',
            'search',
            'clients',
            'optical_distribution',
            'pakets'
        ))->with('mode', 'create_point');
    }

    // Store client baru
    public function store(Request $request)
    {
         
        $request->validate([
    'kode' => 'required|string|max:50|unique:clients,kode',
    'nomor' => 'nullable|string|max:50|unique:clients,nomor',
    'nama' => 'required|string|max:255',
    'alamat' => 'nullable|string|max:500',
    'kordinat' => 'nullable|string',
    'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:50048', // max 50MB
    'id_optical_distribution' => 'nullable|exists:optical_distribution,id_optical_distribution',
    'user_pppoe' => 'nullable|string|max:100|unique:clients,user_pppoe',
    'tanggal_regis' => 'nullable|date',
    'tanggal_pembayaran' => 'nullable|date|after_or_equal:tanggal_regis',
    'id_paket' => 'nullable|exists:paket,id_paket',
    'status' => 'nullable|in:aktif,nonaktif,pending', // contoh pilihan status
]);


        $data = $request->except('foto');

        // Upload foto ke storage/app/public/home
       if ($request->hasFile('foto')) {
    $file = $request->file('foto');
    $filename = time() . '_' . $file->getClientOriginalName();

    // simpan ke disk 'public' di folder home
    $path = $file->storeAs('home', $filename, 'public');

    $data['foto'] = $filename;
}



        Client::create($data);

        return redirect()->route('client.index')->with('success', 'Client berhasil ditambahkan');
    }

    // Halaman edit client
    public function edit(Client $client, Request $request)
    {
        $lines = Line::with('optical_distribution.kategori')->get();
        $kategori = Kategori::all();
        $clients = Client::with(['paket', 'optical_distribution'])->get();
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

        $pakets = Paket::all();

        return view('client.edit', compact(
            'lines',
            'kategori',
            'search',
            'clients',
            'optical_distribution',
            'pakets',
            'client'
        ))->with('mode', 'edit_point');
    }

    // Update client
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kordinat' => 'nullable|string',
            'kode' => 'required|string|max:50|unique:clients,kode,'.$client->id_client.',id_client',
            'user_pppoe' => 'nullable|string|unique:clients,user_pppoe,'.$client->id_client.',id_client',
            'id_paket' => 'nullable|exists:paket,id_paket',
            'id_optical_distribution' => 'nullable|exists:optical_distribution,id_optical_distribution',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('foto');

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/home', $filename);

            // hapus foto lama
            if ($client->foto && \Storage::exists('public/home/'.$client->foto)) {
                \Storage::delete('public/home/'.$client->foto);
            }

            $data['foto'] = $filename;
        }

        $client->update($data);

        return redirect()->route('client.index')->with('success', 'Client berhasil diperbarui');
    }

    // Hapus client
    public function destroy(Client $client)
    {
        // Hapus foto lama
        if ($client->foto && \Storage::exists('public/home/'.$client->foto)) {
            \Storage::delete('public/home/'.$client->foto);
        }

        $client->delete();
        return redirect()->route('client.index')->with('success', 'Client berhasil dihapus');
    }
 
}
