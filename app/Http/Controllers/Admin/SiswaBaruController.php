<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiswaBaru;
use App\Models\User;
use App\Models\ItemSeragam;
use App\Services\SeragamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Tambahkan facade DB


class SiswaBaruController extends Controller
{
    public function __construct(private SeragamService $service)
    {
        // Pindah middleware ke definisi route (Web.php) direkomendasikan di Laravel terbaru,
        // namun tetap aman jika ditaruh di sini.
        // $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $siswa = SiswaBaru::with(['user', 'pembayaran'])->latest()->paginate(15);
        return view('admin.siswa-baru.index', compact('siswa'));
    }

    public function create()
    {
        $items = ItemSeragam::where('stok', '>', 0)->get();
        return view('admin.siswa-baru.create', compact('items'));
    }

    public function store(Request $request)
    {
        // Ambil hanya item yang jumlahnya lebih dari 0
        $items = collect($request->items)
            ->filter(function ($item) {
                return isset($item['jumlah']) && (int) $item['jumlah'] > 0;
            })
            ->values()
            ->toArray();

        // Ganti isi request
        $request->merge([
            'items' => $items
        ]);

        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa_baru,nisn',
            'nama_lengkap' => 'required|string|max:100',
            'kelas' => 'nullable|string|max:20',
            'jurusan' => 'nullable|string|max:100',
            'tahun_masuk' => 'required|digits:4',
            'nama_orang_tua' => 'nullable|string|max:100',
            'no_telp_ortu' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'items' => 'required|array|min:1',
            'items.*.item_seragam_id' => 'required|exists:item_seragam,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);



        // Gunakan Transaksi untuk menjamin data User & Siswa tidak gantung jika pembuatan tagihan gagal
        return DB::transaction(function () use ($request) {
            try {
                // 1. Buat user untuk siswa
                $user = User::create([
                    'name' => $request->nama_lengkap,
                    'email' => $request->email,
                    'password' => Hash::make($request->nisn),
                    'is_active' => true,
                ]);
                $user->assignRole('siswa_baru');

                // 2. Buat data profile siswa baru
                $siswa = SiswaBaru::create([
                    'user_id' => $user->id,
                    'nisn' => $request->nisn,
                    'nama_lengkap' => $request->nama_lengkap,
                    'kelas' => $request->kelas,
                    'jurusan' => $request->jurusan,
                    'tahun_masuk' => $request->tahun_masuk,
                    'nama_orang_tua' => $request->nama_orang_tua,
                    'no_telp_ortu' => $request->no_telp_ortu,
                ]);

                // 3. Generate tagihan seragam
                $this->service->buatTagihan($siswa, $request->items);

                return redirect()->route('admin.siswa-baru.index')
                    ->with('success', "Siswa {$siswa->nama_lengkap} berhasil didaftarkan. Password default: {$request->nisn}");

            } catch (\Exception $e) {
                // Jika terjadi error di dalam block ini, DB::transaction akan otomatis melakukan Rollback
                return back()->withInput()->with('error', 'Pendaftaran Gagal: ' . $e->getMessage());
                // dd($e->getMessage(), $e->getTraceAsString());
            }
        });
    }

    // Pastikan parameter di Route web.php ditulis {siswa_baru} agar sesuai dengan Type-hint model
    public function show(SiswaBaru $siswaBaru)
    {
        $siswaBaru->load(['pembayaran.details.item', 'pembayaran.validator']);
        return view('admin.siswa-baru.show', compact('siswaBaru'));
    }

    public function destroy(SiswaBaru $siswaBaru)
    {
        return DB::transaction(function () use ($siswaBaru) {
            // Jalankan logic pembatalan tagihan jika ada untuk mengembalikan stok seragam ke sistem
            if ($siswaBaru->pembayaran) {
                $this->service->batalTagihan($siswaBaru->pembayaran);
            }

            // Ambil user object sebelum data siswa dihapus
            $user = $siswaBaru->user;

            // Hapus profile siswa baru
            $siswaBaru->delete();

            // Hapus user login-nya
            if ($user) {
                $user->delete();
            }

            return redirect()->route('admin.siswa-baru.index')
                ->with('success', 'Data siswa dan akun pengguna berhasil dihapus dari sistem.');
        });
    }
}