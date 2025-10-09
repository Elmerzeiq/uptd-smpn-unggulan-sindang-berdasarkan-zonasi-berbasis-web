<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Ekskul;
use App\Models\Gallery;
use App\Models\Kategori;
use App\Models\Pengumuman;
use App\Models\GuruDanStaff;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Models\PetunjukTeknis;
use App\Models\AlurPendaftaran;
use App\Models\JadwalPendaftaran;
use App\Models\DokumenPersyaratan;

class HomeController extends Controller
{
    protected $profil;

    public function __construct()
    {
        // Mengambil data profil sekolah sekali saja saat controller diinisialisasi
        $this->profil = ProfilSekolah::first();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profil = ProfilSekolah::first();
        $beritas = Berita::latest()->paginate(8);
        // Mengambil berita terbaru untuk homepage (opsional)
        $latestNews = Berita::with('author')
            ->where('status', 'published')
            ->orderBy('tanggal', 'desc')
            ->take(6)
            ->get();
        $gallery = Gallery::all();
        // Mengambil galeri terbaru untuk homepage (opsional)
        $latestGallery = Gallery::orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $jadwal = JadwalPendaftaran::all();
        $petunjuk = PetunjukTeknis::first();
        $kategoris = Kategori::all();
        $dokumen = DokumenPersyaratan::all();
        $alur = AlurPendaftaran::orderBy('urutan')->get();

        return view('home.index', compact(
            'profil',
            'latestNews',
            'latestGallery',
            'gallery',
            'beritas',
            'jadwal',
            'petunjuk',
            'kategoris',
            'dokumen',
            'alur'
        ));
    }

    public function profilVisiMisi()
    {
        // Variabel $this->profil sudah tersedia dari constructor
        return view('home.profil.visi_misi', ['profil' => $this->profil]);
    }


    public function kontak()
    {
        $profil = ProfilSekolah::first();
        return view('home.kontak', compact('profil'));
    }

    // private function getProfil()
    // {
    //     return ProfilSekolah::first();
    // }

    public function tentangKami()
    {
        $profil = $this->getProfil();
        return view('home.tentang-kami', compact('profil'));
    }

    public function sejarah()
    {
        $profil = $this->getProfil();
        return view('home.profil.sejarah', compact('profil'));
    }

    // public function kurikulum()
    // {
    //     $profil = $this->getProfil();
    //     return view('home.profil.kurikulum', compact('profil'));
    // }

    // public function fasilitas()
    // {
    //     $profil = $this->getProfil();
    //     return view('home.profil.fasilitas', compact('profil'));
    // }

    // public function metodePengajaran()
    // {
    //     $profil = $this->getProfil();
    //     return view('home.metode-pengajaran', compact('profil'));
    // }

    // public function budayaSekolah()
    // {
    //     $profil = $this->getProfil();
    //     return view('home.budaya-sekolah', compact('profil'));
    // }

    public function prestasi()
    {
        $profil = $this->getProfil();
        return view('home.prestasi', compact('profil'));
    }

    public function sambutan()
    {
        // Get the principal (kepala sekolah)
        $kepalaSekolah = GuruDanStaff::where('kategori', 'kepala_sekolah')->first();

        // Get all staff members (teachers and staff)
        $staffs = GuruDanStaff::whereIn('kategori', ['guru', 'staff'])
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get();

        // Get teachers only for the carousel section
        $teachers = GuruDanStaff::where('kategori', 'guru')
            ->orderBy('nama')
            ->get();

        return view('home.profil.sambutan', compact('kepalaSekolah', 'staffs', 'teachers'));
    }

    // Controller Method
    public function Guru()
    {
        $gurus = GuruDanStaff::getAllGuru();
        $staff = GuruDanStaff::getAllStaff();

        return view('home.profil.guru_staff', compact('gurus', 'staff'));
    }


    private function getProfil()
    {
        return ProfilSekolah::firstOrCreate([], [
            'nama_sekolah' => 'Cerulean School',
            'visi' => '',
            'misi' => '',
            'sejarah' => '',
            'jml_siswa' => 0,
            'jml_guru' => 0,
            'jml_staff' => 0,
            'logo_sekolah' => '',
            'image' => '',
            'alamat' => '',
            'kontak1' => '',
            'kontak2' => '',
            'email' => '',
            'prestasi_sekolah' => '',
            'metode_pengajaran' => '',
            'kurikulum' => '',
            'budaya_sekolah' => '',
            'fasilitas_sekolah' => '',
        ]);
    }

    public function kurikulum()
    {
        $profil = $this->getProfil();
        return view('home.profil.kurikulum', compact('profil'));
    }

    public function budayaSekolah()
    {
        $profil = $this->getProfil();
        return view('home.budaya-sekolah', compact('profil'));
    }

    public function fasilitas()
    {
        $profil = $this->getProfil();
        return view('home.profil.fasilitas', compact('profil'));
    }

    public function metodePengajaran()
    {
        $profil = $this->getProfil();
        return view('home.metode-pengajaran', compact('profil'));
    }

    public function prestasiSekolah()
    {
        $profil = $this->getProfil();
        return view('home.prestasi', compact('profil'));
    }

    public function spmb()
    {
        $profil = ProfilSekolah::first();
        $pengumuman = Pengumuman::where('target_penerima','semua')->latest()->get();
        $jadwal = JadwalPendaftaran::all();
        $petunjuk = PetunjukTeknis::first();
        $kategoris = Kategori::all();
        $dokumen = DokumenPersyaratan::all();
        $alur = AlurPendaftaran::orderBy('urutan')->get();

        return view('home.ppdb', compact(
            'profil',
            'pengumuman',
            'jadwal',
            'petunjuk',
            'kategoris',
            'dokumen',
            'alur'
        ));
    }
    // public function jadwal()
    // {

    //     return view('jadwal', compact('jadwal'));
    // }

    // public function petunjuk()
    // {
    //     $petunjuk = PetunjukTeknis::first();
    //     return view('petunjuk', compact('petunjuk'));
    // }

    // public function kategoris()
    // {
    //     $kategoris = Kategori::all();
    //     return view('kategoris', compact('kategoris'));
    // }

    // public function dokumen()
    // {
    //     $dokumen = DokumenPersyaratan::all();
    //     return view('dokumen', compact('dokumen'));
    // }

    // public function alur()
    // {
    //     $alur = AlurPendaftaran::orderBy('urutan')->get();
    //     return view('alur', compact('alur'));
    // }

    /**
     * Display public gallery page.
     */

    /**
     * Display public gallery page.
     */
    public function galeri(Request $request)
    {
        $gallery = Gallery::all();
        return view('home.galeri', compact('gallery'));
    }

    /**
     * Display public news page.
     */
    public function berita(Request $request)
    {
        $beritas = Berita::latest()->paginate(8);
        $query = Berita::with('author')->where('status', 'published');

        // Search berdasarkan judul atau deskripsi
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                    ->orWhere('isi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan bulan/tahun (opsional)
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('tanggal', $request->month)
                ->whereYear('tanggal', $request->year);
        } elseif ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        $items = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        // Data untuk filter tahun
        $years = Berita::where('status', 'published')
            ->selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('home.berita.index', compact('items', 'years', 'beritas'));
    }

    /**
     * Display single news article.
     */
    public function beritaShow($slug)
    {
        // Cari berdasarkan slug terlebih dahulu, jika tidak ada cari berdasarkan ID
        $berita = Berita::with('author')
            ->where('status', 'published')
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->orWhere('id', $slug);
            })
            ->firstOrFail();

        // Berita terkait (dari kategori yang sama atau tanggal yang mirip)
        $relatedNews = Berita::with('author')
            ->where('status', 'published')
            ->where('id', '!=', $berita->id)
            ->orderBy('tanggal', 'desc')
            ->take(3)
            ->get();

        // Berita sebelumnya dan selanjutnya
        $previousNews = Berita::where('status', 'published')
            ->where('tanggal', '<', $berita->tanggal)
            ->orderBy('tanggal', 'desc')
            ->first();

        $nextNews = Berita::where('status', 'published')
            ->where('tanggal', '>', $berita->tanggal)
            ->orderBy('tanggal', 'asc')
            ->first();

        return view('home.berita.show', compact('berita', 'relatedNews', 'previousNews', 'nextNews'));
    }

    /**
     * Display public ekstrakurikuler page.
     */
    public function ekskul(Request $request)
    {
        $query = Ekskul::query();

        // Search berdasarkan judul atau kategori
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('kategori', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $items = $query->orderBy('judul')
            ->paginate(12)
            ->withQueryString();

        // Kategori untuk filter - ambil dari database yang ada
        $categories = Ekskul::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori')
            ->mapWithKeys(function ($kategori) {
                return [$kategori => ucwords(str_replace('_', ' ', $kategori))];
            })
            ->toArray();

        return view('home.ekskul.index', compact('items', 'categories'));
    }

    /**
     * Display single ekstrakurikuler.
     */
    public function ekskulShow($id)
    {
        $ekskul = Ekskul::findOrFail($id);

        // Ekstrakurikuler terkait (dari kategori yang sama atau random)
        $relatedEkskul = Ekskul::where('id', '!=', $ekskul->id);

        if ($ekskul->kategori) {
            $relatedEkskul->where('kategori', $ekskul->kategori);
        }

        $relatedEkskul = $relatedEkskul->take(4)->get();

        // Jika tidak ada yang sama kategori, ambil random
        if ($relatedEkskul->count() < 4) {
            $additionalEkskul = Ekskul::where('id', '!=', $ekskul->id)
                ->whereNotIn('id', $relatedEkskul->pluck('id'))
                ->inRandomOrder()
                ->take(4 - $relatedEkskul->count())
                ->get();

            $relatedEkskul = $relatedEkskul->merge($additionalEkskul);
        }

        // Kategori untuk sidebar
        $categories = Ekskul::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori')
            ->mapWithKeys(function ($kategori) {
                return [$kategori => ucwords(str_replace('_', ' ', $kategori))];
            })
            ->toArray();

        return view('home.ekskul.show', compact('ekskul', 'relatedEkskul', 'categories'));
    }

    /**
     * Get gallery by category (AJAX endpoint - opsional).
     */
    public function getGalleryByCategory(Request $request)
    {
        $category = $request->get('category');

        $query = Gallery::query();

        if ($category && $category !== 'semua') {
            $query->where('kategori', $category);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'deskripsi' => $item->deskripsi,
                    'kategori' => $item->kategori,
                    'image_url' => $item->image ? asset('storage/' . $item->image) : null,
                ];
            })
        ]);
    }

    /**
     * Search news (AJAX endpoint - opsional).
     */
    public function searchBerita(Request $request)
    {
        $query = $request->get('q');

        $news = Berita::with('author')
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('judul', 'like', '%' . $query . '%')
                    ->orWhere('deskripsi', 'like', '%' . $query . '%');
            })
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $news->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'deskripsi' => $item->deskripsi,
                    'slug' => $item->slug,
                    'tanggal' => $item->tanggal,
                    'author' => $item->author->name ?? 'Unknown',
                    'image_url' => $item->image ? asset('storage/' . $item->image) : null,
                    'url' => route('home.berita.show', $item->slug ?? $item->id)
                ];
            })
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }


}
