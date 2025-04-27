<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
            ];
            
            $page = (object) [
                'title' => 'Daftar user yang terdaftar dalam sistem'
            ];
            
            $activeMenu = 'user'; // set menu yang sedang aktif
            
            $level = LevelModel::all(); // ambil data level untuk filter level

            return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        // Filter data user berdasarkan level_id
        if ($request->level_id){
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('level', function ($user) {
                return $user->level 
                    ? $user->level->level_nama . ' (' . $user->level->level_kode . ')' 
                    : 'Tidak ada level';
            })
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')" 
                class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" 
                class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" 
                class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create() 
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];
        
        $level = LevelModel::all(); // Ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif
        
        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
        
    }

    public function store(Request $request)
    {
        $request->validate([
            //username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer'
        ]);
        
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->name,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);
        
        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    //Menampilkan detail user
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);
        
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];
        
        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    //Menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);

    }

    //Menyimpan data user baru
    public function update(Request $request, string $id)
    {
        $request->validate([
            //username harus diisi, berupa string, minimal 3 karakter, dan 
            //bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang di edit
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama'     => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5',          // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
        ]);
        
        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);
        
        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    // Menghapus data user
    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error','Data User Tidak ditemukan');
        }

        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success','Data User berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan pesan error
            return redirect('/user')->with('error','Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_name' )->get();

        return view('user.create_ajax' )
                    ->with('level', $level);
    }
    
    public function store_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama'     => 'required|string|max:100',
                'password' => 'required|min:5',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            UserModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_name')->get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        // cek apakah ada request ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id'  => 'required|integer',
                'username'  => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama'      => 'required|max:100',
                'password'  => 'nullable|min:5|max:20'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false, // respon json, true jika validasi berhasil, false jika gagal
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors() // ambil pesan error dari validator
                ]);
            }

            $check = UserModel::find($id);
            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, hapus dari request
                    $request->request->remove('password');
                }

                $check->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $user = UserModel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);

            if ($user) {
                $user->delete();

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

    //Menampilkan form import user
    public function import()
    {
        return view('user.import');
    }
    //Proses import user dari file excel
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_user');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
    
            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $row => $value) {
                    if ($row > 1) { // Skip header row
                        $insert[] = [
                            'user_id' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'level_id' => $value['D'],
                            'password' => bcrypt('password'), // Default password; adjust as needed
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    UserModel::insertOrIgnore($insert);
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

    public function export_excel()
        {
        // Ambil data users yang akan diekspor
            $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
                ->orderBy('user_id')
                ->with('level')
                ->get();
 
            // Load library PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
 
            // Set header kolom
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'User ID');
            $sheet->setCellValue('C1', 'Username');
            $sheet->setCellValue('D1', 'Nama');
            $sheet->setCellValue('E1', 'Level');
 
            // Format header bold
            $sheet->getStyle('A1:E1')->getFont()->setBold(true);
 
            // Isi data users
            $no = 1;
            $baris = 2;
            foreach ($users as $user) {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $user->user_id);
                $sheet->setCellValue('C' . $baris, $user->username);
                $sheet->setCellValue('D' . $baris, $user->nama);
                $sheet->setCellValue('E' . $baris, $user->level->level_nama);
                $baris++;
                $no++;
            }
 
            // Set auto size untuk kolom
            foreach (range('A', 'E') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
 
            // Set title sheet
            $sheet->setTitle('Data User');
             
            // Generate filename
            $filename = 'Data_User_' . date('Y-m-d_H-i-s') . '.xlsx';
 
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

        //Export PDF
        public function export_pdf()
        {
            set_time_limit(0); // set waktu eksekusi tidak terbatas
            
             $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
                 ->orderBy('user_id')
                 ->with('level')
                 ->get();
 
             // use Barryvdh\DomPDF\Facade\Pdf;
             $pdf = Pdf::loadView('user.export_pdf', ['user' => $users]);
             $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
             $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
             $pdf->render();
 
             return $pdf->stream('Data user ' . date('Y-m-d H:i:s') . '.pdf');
        }

        //Menambahkan profile
        public function profile_page()
     {
         $user = auth()->user();
 
         $breadcrumb = (object) [
             'title' => 'User Profile',
             'list' => ['Home', 'Profile']
         ];
 
         $page = (object) [
             'title' => 'User Profile'
         ];
 
         $activeMenu = 'profile';
 
         return view('user.profile', ['user' => $user, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
     public function update_picture(Request $request)
     {
         // Validasi file
         $request->validate([
             'user_profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
         ]);
 
         try {
             $user = auth()->user();
 
             if (!$user) {
                 return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
             }
 
             $userId = $user->user_id;
 
             $userModel = UserModel::find($userId);
 
             if (!$userModel) {
                 return redirect('/login')->with('error', 'User tidak ditemukan');
             }
 
             // Menghapus foto jika sudah ada
             if ($userModel->user_profile_picture && file_exists(storage_path('app/public/' . $userModel->user_profile_picture))) {
                 Storage::disk('public')->delete($userModel->user_profile_picture);
             }
 
             $fileName = 'profile_' . $userId . '_' . time() . '.' . $request->user_profile_picture->extension();
             $path = $request->user_profile_picture->storeAs('profiles', $fileName, 'public');
 
             UserModel::where('user_id', $userId)->update([
                 'user_profile_picture' => $path
             ]);

             
             return redirect('/user/profile')->with('success', 'Foto profile berhasil diperbarui');


         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
         }
     }
}