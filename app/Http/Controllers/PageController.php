<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PageController extends Controller
{
    public function home()
    {
        $totalPoints    = DB::table('points')->count();
        $totalPolygons  = DB::table('polygons')->count();
        $totalPolylines = DB::table('polylines')->count();
        $totalLuas      = DB::table('polygons')->sum('luas_ha') ?? 0;

        return view('home', compact(
            'totalPoints',
            'totalPolygons',
            'totalPolylines',
            'totalLuas'
        ));
    }

    public function mapAll()
    {
        return view('map-all');
    }

    public function tentang()
    {
        return view('tentang');
    }

    // ─── AUTH ─────────────────────────────────────────────

    public function loginForm()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return redirect()
                ->intended('/dashboard')
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout.');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::attempt($request->only('email', 'password'));

        return redirect('/dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang.');
    }

    // ─── DASHBOARD ─────────────────────────────────────────────

    public function dashboard()
    {
        $totalPoints    = DB::table('points')->count();
        $totalPolygons  = DB::table('polygons')->count();
        $totalPolylines = DB::table('polylines')->count();
        $totalLuas      = DB::table('polygons')->sum('luas_ha') ?? 0;

        $rekapPerubahan = DB::select("
            SELECT penggunaan_lama, penggunaan_baru, COUNT(*) as jumlah,
            COALESCE(SUM(luas_ha), 0) as total_luas
            FROM polygons
            GROUP BY penggunaan_lama, penggunaan_baru
            ORDER BY jumlah DESC
            LIMIT 10
        ");

        $rekapKategori = DB::select("
            SELECT kategori_objek, COUNT(*) as jumlah,
            COALESCE(SUM(luas_ha), 0) as total_luas
            FROM polygons
            GROUP BY kategori_objek
            ORDER BY total_luas DESC
        ");

        $perKecamatan = DB::select("
            SELECT kecamatan, COUNT(*) as jumlah
            FROM polygons
            GROUP BY kecamatan
            ORDER BY jumlah DESC
            LIMIT 10
        ");

        $perTahun = DB::select("
            SELECT tahun_perubahan, COUNT(*) as jumlah
            FROM polygons
            GROUP BY tahun_perubahan
            ORDER BY tahun_perubahan
        ");

        // ─── BPS DATA ─────────────────────────────────────────────

        $tahunBps = DB::table('bps_lahan')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->limit(2)
            ->pluck('tahun');

        $tahunBps = $tahunBps ?? collect();

        $tahunLama = $tahunBps->last();
        $tahunBaru = $tahunBps->first();

        $bpsTotalPerTahun = DB::table('bps_lahan')
            ->select('tahun')
            ->selectRaw('
                COALESCE(SUM(luas_pertanian_ha),0) as pertanian,
                COALESCE(SUM(luas_terbangun_ha),0) as terbangun,
                COALESCE(SUM(luas_lainnya_ha),0) as lainnya
            ')
            ->whereIn('tahun', $tahunBps)
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        return view('dashboard', compact(
            'totalPoints',
            'totalPolygons',
            'totalPolylines',
            'totalLuas',
            'rekapPerubahan',
            'rekapKategori',
            'perKecamatan',
            'perTahun',
            'bpsTotalPerTahun',
            'tahunLama',
            'tahunBaru'
        ));
    }
}
