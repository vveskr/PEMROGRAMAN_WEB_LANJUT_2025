<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
        $levels = LevelModel::select('level_id', 'level_kode', 'level_name');
        
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
            'level_name' => 'required|string'
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_name' => $request->level_name
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
            'level_name' => 'required|string'
        ]);

        $level = LevelModel::findOrFail($id);
        $level->update([
            'level_kode' => $request->level_kode,
            'level_name' => $request->level_name
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
        $level = LevelModel::select('level_id', 'level_name' )->get();

        return view('level.create_ajax' )
                    ->with('level', $level);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'level_kode' => 'required|string|max:5|unique:m_level,level_kode',
                'level_name' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi Gagal', 'msgField' => $validator->errors()]);
            }

            LevelModel::create($request->all());
            return response()->json(['status' => true, 'message' => 'Data level berhasil disimpan']);
        }
        return redirect('/');
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
                'level_name' => 'required|string|max:100',
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

    // Import data level
    public function import()
    {
        return view('level.import');
    }

     //Mengimport data level dari file excel
     public function import_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'file_level' => ['required', 'mimes:xlsx', 'max:1024']
             ];
 
             $validator = Validator::make($request->all(), $rules);
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors()
                 ]);
             }
 
             $file = $request->file('file_level');
             $reader = IOFactory::createReader('Xlsx');
             $reader->setReadDataOnly(true);
             $spreadsheet = $reader->load($file->getRealPath());
             $sheet = $spreadsheet->getActiveSheet();
             $data = $sheet->toArray(null, false, true, true);
 
             $insert = [];
             if (count($data) > 1) {
                 foreach ($data as $baris => $value) {
                     if ($baris > 1) { // baris ke 1 adalah header
                         $insert[] = [
                             'level_id' => $value['A'],
                             'level_kode' => $value['B'],
                             'level_name' => $value['C'],
                             'created_at' => now(),
                         ];
                     }
                 }
 
                 if (count($insert) > 0) {
                     LevelModel::insertOrIgnore($insert);
                     return response()->json([
                         'status' => true,
                         'message' => 'Data berhasil diimport'
                     ]);
                 }
             }
 
             return response()->json([
                 'status' => false,
                 'message' => 'Tidak ada data yang diimport'
             ]);
         }
 
         return redirect('/');
     }

     // Export data level ke file excel
     public function export_excel()
     {
          // Ambil data level yang akan diekspor
          $levels = LevelModel::select('level_id', 'level_kode', 'level_name')->orderBy('level_id')->get();
  
          // Load library PhpSpreadsheet
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
  
          // Set header kolom
          $sheet->setCellValue('A1', 'No');
          $sheet->setCellValue('B1', 'ID Level');
          $sheet->setCellValue('C1', 'Kode Level');
          $sheet->setCellValue('D1', 'Nama Level');
  
          // Format header bold
          $sheet->getStyle('A1:D1')->getFont()->setBold(true);
  
          // Isi data level
          $no = 1;
          $baris = 2;
          foreach ($levels as $level) {
              $sheet->setCellValue('A' . $baris, $no);
              $sheet->setCellValue('B' . $baris, $level->level_id);
              $sheet->setCellValue('C' . $baris, $level->level_kode);
              $sheet->setCellValue('D' . $baris, $level->level_name);
              $baris++;
              $no++;
          }
  
          // Set auto size untuk kolom
          foreach (range('A', 'D') as $columnID) {
              $sheet->getColumnDimension($columnID)->setAutoSize(true);
          }
  
          // Set title sheet
          $sheet->setTitle('Data Level');
          
          // Generate filename
          $filename = 'Data_Level_' . date('Y-m-d_H-i-s') . '.xlsx';
  
          // Set header untuk download file
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment;filename="' . $filename . '"');
          header('Cache-Control: max-age=0');
          header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
          header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
          header('Cache-Control: cache, must-revalidate');
          header('Pragma: public');
  
          $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
          $writer->save('php://output');
          exit;
     }
}