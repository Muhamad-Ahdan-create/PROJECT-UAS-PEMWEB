<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemSeragam;
use Illuminate\Http\Request;

class ItemSeragamController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $items = ItemSeragam::orderBy('nama_item')->paginate(20);
        return view('admin.item-seragam.index', compact('items'));
    }

    public function create()
    {
        return view('admin.item-seragam.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
            'ukuran'    => 'nullable|string|max:20',
            'stok'      => 'required|integer|min:0',
        ]);

        ItemSeragam::create($request->only(['nama_item', 'harga', 'ukuran', 'stok']));

        return redirect()->route('admin.item-seragam.index')
            ->with('success', 'Item seragam baru berhasil ditambahkan.');
    }

    public function edit(ItemSeragam $itemSeragam)
    {
        return view('admin.item-seragam.edit', compact('itemSeragam'));
    }

    public function update(Request $request, ItemSeragam $itemSeragam)
    {
        $request->validate([
            'nama_item' => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
            'ukuran'    => 'nullable|string|max:20',
            'stok'      => 'required|integer|min:0',
        ]);

        $itemSeragam->update($request->only(['nama_item', 'harga', 'ukuran', 'stok']));

        return redirect()->route('admin.item-seragam.index')
            ->with('success', 'Data item seragam berhasil diperbarui.');
    }

    public function destroy(ItemSeragam $itemSeragam)
    {
        // Validasi Bisnis: Cek apakah item seragam ini sudah pernah dipakai di transaksi manapun
        // Asumsi nama relasi di Model ItemSeragam ke DetailSeragam adalah 'details'
        if ($itemSeragam->details()->exists()) {
            return back()->with('error', 'Item tidak dapat dihapus karena memiliki riwayat transaksi/tagihan siswa. Solusi: Ubah stok menjadi 0 agar tidak dapat dipilih lagi.');
        }

        $itemSeragam->delete();

        return redirect()->route('admin.item-seragam.index')
            ->with('success', 'Item seragam berhasil dihapus dari sistem.');
    }
}