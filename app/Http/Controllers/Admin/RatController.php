<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RatCatatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RatController extends Controller
{
    public function __construct()
    {
      
    }

    public function index()
    {
        $catatan = RatCatatan::with('pembuat')
            ->orderBy('tahun_rat', 'desc')
            ->paginate(10);
        return view('admin.rat.index', compact('catatan'));
    }

    public function create()
    {
        return view('admin.rat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_rat' => 'required|digits:4|integer',
            'tanggal_rat' => 'required|date',
            'tempat' => 'required|string|max:200',
            'agenda' => 'required|string',
            'notulensi' => 'nullable|string',
            'hasil_keputusan' => 'nullable|string',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('dokumen_lampiran')) {
            $lampiranPath = $request->file('dokumen_lampiran')
                ->store('rat-lampiran', 'public');
        }

        RatCatatan::create([
            'dibuat_oleh' => Auth::id(),
            'tahun_rat' => $request->tahun_rat,
            'tanggal_rat' => $request->tanggal_rat,
            'tempat' => $request->tempat,
            'agenda' => $request->agenda,
            'notulensi' => $request->notulensi,
            'hasil_keputusan' => $request->hasil_keputusan,
            'dokumen_lampiran' => $lampiranPath,
        ]);

        return redirect()->route('admin.rat.index')
            ->with('success', 'Catatan RAT berhasil disimpan.');
    }

    public function show(RatCatatan $rat)
    {
        $rat->load('pembuat');
        return view('admin.rat.show', compact('rat'));
    }

    public function edit(RatCatatan $rat)
    {
        return view('admin.rat.edit', compact('rat'));
    }

    public function update(Request $request, RatCatatan $rat)
    {
        $request->validate([
            'agenda' => 'required|string',
            'notulensi' => 'nullable|string',
            'hasil_keputusan' => 'nullable|string',
        ]);

        $rat->update($request->only(['agenda', 'notulensi', 'hasil_keputusan', 'tempat']));

        return redirect()->route('admin.rat.index')
            ->with('success', 'Catatan RAT diperbarui.');
    }

    public function destroy(RatCatatan $rat)
    {
        $rat->delete();
        return redirect()->route('admin.rat.index')
            ->with('success', 'Catatan RAT dihapus.');
    }
    public function cetakPdf(RatCatatan $rat)
    {
        $rat->load('pembuat');
        $pdf = Pdf::loadView('pdf.rat-catatan', compact('rat'))
            ->setPaper('a4', 'portrait');
        return $pdf->download("catatan-rat-{$rat->tahun_rat}.pdf");
    }

}
