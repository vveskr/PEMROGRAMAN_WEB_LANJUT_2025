<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    // Menampilkan halaman daftar level
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level yang tersedia dalam sistem'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif
        
        $level = LevelModel::all();

        return view('level.index', compact('breadcrumb', 'level', 'page', 'activeMenu'));
    }

    // Mengambil data level dalam format JSON untuk DataTables
    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');
        
        return DataTables::of($levels)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman tambah level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Level Baru'
        ];

        $activeMenu = 'level';

        return view('level.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Menyimpan data level baru
    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|unique:m_level,level_kode',
            'level_nama' => 'required|string'
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Data Level berhasil ditambahkan');
    }

    // Menampilkan halaman edit level
    public function edit($id)
    {
        $level = LevelModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Data Level'
        ];

        $activeMenu = 'level';

        return view('level.edit', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    // Menyimpan perubahan data level
    public function update(Request $request, $id)
    {
        $request->validate([
            'level_kode' => 'required|string|unique:m_level,level_kode,' . $id . ',level_id',
            'level_nama' => 'required|string'
        ]);

        $level = LevelModel::findOrFail($id);
        $level->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Data Level berhasil diperbarui');
    }

    // Menghapus data level
    public function destroy($id)
    {
        $level = LevelModel::find($id);

        if (!$level) {
            return redirect('/level')->with('error', 'Data Level tidak ditemukan');
        }

        try {
            $level->delete();
            return redirect('/level')->with('success', 'Data Level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data Level gagal dihapus karena masih terkait dengan data lain');
        }
    }

    public function show($id)
    {
    $level = LevelModel::findOrFail($id);

    $breadcrumb = (object) [
        'title' => 'Detail Level',
        'list' => ['Home', 'Level', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail Level'
    ];

    $activeMenu = 'level';

    return view('level.show', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama' )->get();

        return view('level.create_ajax' )
                    ->with('level', $level);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'level_kode' => 'required|string|max:5|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi Gagal', 'msgField' => $validator->errors()]);
            }

            LevelModel::create($request->all());
            return response()->json(['status' => true, 'message' => 'Data level berhasil disimpan']);
        }
        return redirect('/');
    }

    public function show_ajax($id)
    {
        $level = LevelModel::find($id);
        return view('level.show_ajax', compact('level'));
    }

    public function edit_ajax(string $id)
    {
        return view('level.edit_ajax', ['level' => LevelModel::find($id)]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'level_kode' => 'required|string|max:5',
                'level_nama' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi Gagal', 'msgField' => $validator->errors()]);
            }

            $level = LevelModel::find($id);
            if ($level) {
                $level->update($request->all());
                return response()->json(['status' => true, 'message' => 'Data level berhasil diubah']);
            }
            return response()->json(['status' => false, 'message' => 'Data level tidak ditemukan']);
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        return view('level.confirm_ajax', ['level' => LevelModel::find($id)]);
    }

    // Menghapus data level
    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
            }
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        return redirect('/');
    }
}