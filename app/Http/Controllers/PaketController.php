<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $paket = Paket::latest()->paginate(10);
        return view('paket.index', compact('paket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'nullable|string|max:100',
            'harga' => 'nullable|numeric',
        ]);

        Paket::create($request->all());
        return redirect()->route('paket.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'nullable|string|max:100',
            'harga' => 'nullable|numeric',
        ]);

        $paket->update($request->all());
        return redirect()->route('paket.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Paket $paket)
    {
        if ($paket->clients()->count() > 0) {
            return redirect()->route('paket.index')->with('error', 'Paket tidak dapat dihapus karena sedang digunakan oleh client.');
        }

        $paket->delete();
        return redirect()->route('paket.index')->with('success', 'Paket berhasil dihapus.');
    }
}
