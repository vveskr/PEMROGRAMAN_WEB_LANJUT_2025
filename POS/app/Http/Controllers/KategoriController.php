<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Kategori;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    // Menampilkan Halaman Awal kategori
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];
        
        $page = (object) [
            'title' => 'Daftar Kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori';

        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori
        ]);
    }

    // Ambil data kategori dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $kategori = kategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama', 'kategori_slug');

        return DataTables::of($kategori)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                     '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                     '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                     '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create() 
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Kategori baru'
        ];
        
        $kategori = KategoriModel::all(); // Ambil data kategori untuk ditampilkan di form
        $activeMenu = 'kategori'; // set menu yang sedang aktif
        
        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
        
    }
    
    //Menyimpan data kategori baru
    public function store(Request $request)
    {
        $request->validate([
            //kategori kode harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_kategori kolom kategorin_kode
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100'
        ]);
        
        kategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        
        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }
    
    //Menampilkan detail kategori
    public function show(string $id)
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list'  => ['Home', 'Kategori', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    
    //Menampilkan halaman form edit kategori
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif

        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    
    //Menyimpan data kategori baru
    public function update(Request $request, string $id)
    {
        $request->validate([
            //kategori kode harus diisi, berupa string, minimal 3 karakter, dan 
            //bernilai unik di tabel m_kategori kolom kategori_kode kecuali untuk kategori dengan id yang sedang di edit
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100' // nama harus diisi, berupa string, dan maksimal 100 karakter
        ]);
        
        kategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        
        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    // Menghapus data kategori
    public function destroy(string $id)
    {
        $check = KategoriModel::find($id);
        if (!$check) {
            return redirect('/kategori')->with('error','Data kategori Tidak ditemukan');
        }

        try {
            KategoriModel::destroy($id);
            return redirect('/kategori')->with('success','Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan pesan error
            return redirect('/kategori')->with('error','Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|max:100',
                'kategori_slug' => 'required|string|max:100',
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil   
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    public function show_ajax($id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.show_ajax', compact('kategori'));
    }

    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|max:10',
                'kategori_nama' => 'required|string|max:100',
                'kategori_slug' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $check = KategoriModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori berhasil diubah',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kategori tidak ditemukan',
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);

            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function import()
     {
         return view('kategori.import');
     }  

    public function import_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             // Validasi file
             $rules = [
                 'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
             ];
             $validator = Validator::make($request->all(), $rules);
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors()
                 ]);
             }
     
             // Proses file
             $file = $request->file('file_kategori');
             $reader = IOFactory::createReader('Xlsx');
             $reader->setReadDataOnly(true);
             $spreadsheet = $reader->load($file->getRealPath());
             $sheet = $spreadsheet->getActiveSheet();
             $data = $sheet->toArray(null, false, true, true);
     
             $insert = [];
             if (count($data) > 1) {
                 foreach ($data as $row => $value) {
                     if ($row > 1) { // Lewati header (baris pertama)
                         $insert[] = [
                             'kategori_kode' => $value['A'], // Ambil data dari kolom A
                             'kategori_nama' => $value['B'], // Ambil data dari kolom B
                             'created_at' => now(),
                         ];
                     }
                 }
                 if (count($insert) > 0) {
                     KategoriModel::insertOrIgnore($insert);
                     return response()->json([
                         'status' => true,
                         'message' => 'Data kategori berhasil diimport'
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

     // Export 
    public function export_excel()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kategori');
        $sheet->setCellValue('C1', 'Nama Kategori');
        
        // Ambil Data dari Database
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')->get();
        
        $row = 2; // Mulai dari baris kedua setelah header
        $no = 1;
        
        foreach ($kategori as $data) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $data->kategori_kode);
            $sheet->setCellValue('C' . $row, $data->kategori_nama);
            $row++;
        }
        
        // Set AutoSize untuk Kolom
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    
        // Set Border untuk Sel
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:C' . ($row - 1))->applyFromArray($styleArray);
        
        // Simpan File Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Data_Kategori_' . date('Y-m-d_H-i-s') . '.xlsx';
    
        // Simpan ke Storage Laravel sementara sebelum di-download
        $filePath = storage_path('app/public/' . $fileName);
        $writer->save($filePath);
    
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    //Export PDF
    public function export_pdf()
        {
            set_time_limit(0); // set waktu eksekusi tidak terbatas

             $kategori = KategoriModel::select('kategori_kode', 'kategori_nama', 'kategori_slug')
                 ->orderBy('kategori_id')
                 ->get();
 
             // use Barryvdh\DomPDF\Facade\Pdf;
             $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
             $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
             $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
             $pdf->render();
 
             return $pdf->stream('Data kategori ' . date('Y-m-d H:i:s') . '.pdf');
        }
}