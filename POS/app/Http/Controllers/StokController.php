<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class StokController extends Controller
{
    // Menampilkan halaman utama stok
    public function index()
    {
        $activeMenu = 'stok';
        $breadcrumb = (object) [
            'title' => 'Data Stok',
            'list' => ['Home', 'Stok']
        ];

        $barang = BarangModel::select('barang_id', 'barang_nama')->get();

        return view('stok.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'barang' => $barang
        ]);
    }

    // Mengambil data stok untuk DataTables
    public function list(Request $request)
    {
        try {
            $stok = StokModel::with(['barang', 'user'])
                ->select('t_stok.*'); // Gunakan select lengkap

            // Filter berdasarkan barang
            $barang_id = $request->input('filter_barang');
            if (!empty($barang_id)) {
                $stok->where('barang_id', $barang_id);
            }
            // Handle server-side parameters
            return DataTables::eloquent($stok)
                ->addIndexColumn()
                ->addColumn('barang_kode', fn($s) => $s->barang->barang_kode ?? '-')
                ->addColumn('barang_nama', fn($s) => $s->barang->barang_nama ?? '-')
                ->addColumn('nama', fn($s) => $s->user->nama ?? 'System')
                ->addColumn('updated_at', function ($s) {
                    return $s->updated_at ? $s->updated_at->toISOString() : null;
                })
                ->addColumn('aksi', function ($s) {
                    return '<div class="text-center">' .
                        '<button onclick="modalAction(\'' . url('/stok/' . $s->stok_id . '/edit_ajax') . '\')" class="btn btn-sm btn-warning mr-1">Edit</button>' .
                        '<button onclick="modalAction(\'' . url('/stok/' . $s->stok_id . '/delete_ajax') . '\')" class="btn btn-sm btn-danger">Hapus</button>' .
                        '</div>';
                })
                ->rawColumns(['aksi'])
                ->toJson();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // Menampilkan form create dengan AJAX
    public function create_ajax()
    {
        // Ambil barang yang belum ada di tabel stok
        $barang = BarangModel::whereNotIn('barang_id', function ($query) {
            $query->select('barang_id')->from('t_stok');
        })->select('barang_id', 'barang_nama')->get();

        return view('stok.create_ajax', ['barang' => $barang]);
    }

    // Menyimpan data stok baru dengan AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id' => 'required|integer|exists:m_barang,barang_id',
                'stok_jumlah' => 'required|integer|min:1' // Ubah min ke 1
            ];

            $messages = [
                'barang_id.required' => 'Pilih barang wajib diisi',
                'stok_jumlah.min' => 'Stok minimal 1'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors() // Sesuaikan dengan key yang dipakai di view
                ], 422);
            }

            try {
                StokModel::create([
                    'barang_id' => $request->barang_id,
                    'user_id' => auth()->user()->user_id,
                    'stok_jumlah' => $request->stok_jumlah
                    // created_at dan updated_at akan terisi otomatis
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Stok berhasil ditambahkan'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        }
        return redirect('/');
    }
    // Menampilkan form edit dengan AJAX
    public function edit_ajax($id)
    {
        try {
            $stok = StokModel::findOrFail($id);
            return view('stok.edit_ajax', ['stok' => $stok]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return view('stok.edit_ajax')->with('error', 'Data stok tidak ditemukan');
        }
    }

    // Update data dengan AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'stok_jumlah' => 'required|integer|min:1'
            ];

            $messages = [
                'stok_jumlah.min' => 'Stok minimal 1'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ], 422);
            }

            try {
                $stok = StokModel::findOrFail($id);
                $stok->update([
                    'stok_jumlah' => $request->stok_jumlah
                    // updated_at akan terupdate otomatis
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Stok berhasil diperbarui'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal update data: ' . $e->getMessage()
                ], 500);
            }
        }
        return redirect('/');
    }

    // Menampilkan konfirmasi hapus dengan AJAX
    public function confirm_ajax($id)
    {
        try {
            $stok = StokModel::with('barang')->findOrFail($id);
            return view('stok.confirm_ajax', ['stok' => $stok]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return view('stok.confirm_ajax')->with('error', 'Data stok tidak ditemukan');
        }
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $stok = StokModel::findOrFail($id);
                $stok->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menghapus stok: ' . $e->getMessage()
                ], 500);
            }
        }
        return redirect('/');
    }
    
    public function export_excel()
    {
        // Ambil data stok dengan relasi barang dan user
        $stok = StokModel::with(['barang', 'user'])
            ->orderBy('barang_id')
            ->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Jumlah Stok');
        $sheet->setCellValue('E1', 'Diupdate Oleh');
        $sheet->setCellValue('F1', 'Terakhir Update');

        // Format header bold
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $row = 2;
        foreach ($stok as $s) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $s->barang->barang_kode ?? '-');
            $sheet->setCellValue('C' . $row, $s->barang->barang_nama ?? '-');
            $sheet->setCellValue('D' . $row, $s->stok_jumlah);
            $sheet->setCellValue('E' . $row, $s->user->nama ?? 'System');
            $sheet->setCellValue('F' . $row, $s->updated_at->format('d-m-Y H:i'));
            $row++;
            $no++;
        }

        // Auto size kolom
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Format angka stok
        $sheet->getStyle('D2:D' . $row)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // Set judul sheet
        $sheet->setTitle('Data Stok');

        // Generate filename
        $filename = 'Data_Stok_' . date('Y-m-d_His') . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $stok = StokModel::with(['barang', 'user'])
            ->orderBy('barang_id')
            ->get();

        $pdf = Pdf::loadView('stok.export_pdf', [
            'stok' => $stok,
            'tanggal' => now()->format('d-m-Y H:i:s')
        ]);

        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption("isRemoteEnabled", true);

        return $pdf->stream('Data_Stok_' . date('YmdHis') . '.pdf');
    }
}