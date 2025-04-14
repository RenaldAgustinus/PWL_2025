<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ViewErrorBag;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Mockery\Matcher\HasKey;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\error;

class UserController extends Controller
{

    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object)[
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif
        $level = LevelModel::all();// ambil data level untuk filter data 

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables public function list(Request $request)    
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        // Filter data user berdasarkan level_id
        if ($request->level_id) {   
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn() // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($user) {
                    /* 
                $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id).'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                */

                $btn  = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }



    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all();//ambil data level untuk ditampilkan di form 
        $activeMenu = 'user';

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request){
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama'     => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => bcrypt($request->password),// enkripsi pass
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('Success', 'Data berhasil disimpan');
    }

    public function show(string $id){
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object)[
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail user'
        ];

        $activeMenu = 'user';

        return view('user.show',['user' => $user, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id){
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit'],
        ];

        $page = (object)[
            'title' => 'Edit user'
        ];

        $activeMenu = 'user';

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,'.$id.',user_id',
            'nama'     => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer'
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success',' Data berhasil diubah');
    }

    public function destroy(string $id){
        $check = UserModel::find($id);

        if (!$check) { // cek data ada atau tidak
            return redirect('/user')->with('error','data tidak ditemukan');
        }

        try{
            UserModel::destroy($id);// hapus data level
            return redirect('/user')->with('success','Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e){
            return redirect('/user')->with('error', 'Data user gagal dihapus karena terdapat tabel lain yang masih terkait dengan data ini');
        }
    }

    public function create_ajax(){
        $level = LevelModel::select('level_id','level_nama')->get();

        return View('user.create_ajax')
        ->with('level', $level);
    }

    public function store_ajax(Request $request){
        //cek req ajax
        if ($request->ajax()|| $request->wantsJson()) {
            $rules =[
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama'     => 'required|string|max:100',
                'password' => 'required|min:6',

            ]; 
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, //response status
                    'message' => 'Validasi Gagal', //pesan gagal
                    'msgField' => $validator->errors(), //pesan error
                ]);
            }
            UserModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

        public function edit_ajax(string $id){
            $user = UserModel::find($id);
            $level = LevelModel::select('level_id', 'level_nama')->get();

            return view('user.edit_ajax',['user'=> $user, 'level' => $level]);
        }

        public function update_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id'  => 'required|integer',
                'username'  => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama'      => 'required|max:100',
                'password'  => 'nullable|min:6|max:20'
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false, // Respon JSON, true: berhasil, false: gagal
                    'message'   => 'Validasi gagal.',
                    'msgField'  => $validator->errors() // Menunjukkan field mana yang error
                ]);
            }

            // Cek apakah user dengan ID yang diberikan ada
            $check = UserModel::find($id);
            if ($check) {
                // Jika password tidak diisi, hapus dari request
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }

                // Update data user
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

    public function delete_ajax(Request $request, $id){
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'data tidak ditemukan'
                ]);
            }
        }
    }

    public function import()
    {
        return view('user.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
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
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { 
                        $insert[] = [
                            'level_id'   => $value['A'],
                            'username'   => $value['B'],
                            'nama'       => $value['C'],
                            'password'   => bcrypt($value['D']), 
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    UserModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Data user berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
    {
        $user = UserModel::select('level_id', 'username', 'nama', 'password')
                         ->orderBy('level_id')
                         ->with('level')
                         ->get();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); 
    
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Password');
        $sheet->setCellValue('E1', 'Level Pengguna');
    
        $sheet->getStyle('A1:E1')->getFont()->setBold(true); 
    
        $no = 1; 
        $baris = 2; 
        foreach ($user as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->username);
            $sheet->setCellValue('C'.$baris, $value->nama);
            $sheet->setCellValue('D'.$baris, $value->password); 
            $sheet->setCellValue('E'.$baris, $value->level->level_nama); 
            $baris++;
            $no++;
        }
        
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);          
        }
    
        $sheet->setTitle('Data User'); 
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data User '.date('Y-m-d H:i:s').'.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        
        $writer->save('php://output');
        exit;    
    }
    public function export_pdf(){
        $user = UserModel::select('level_id', 'user_id', 'username', 'nama')
                    ->orderBy('level_id')
                    ->orderBy('user_id')
                    ->with('level')
                    ->get();

        $pdf = Pdf::loadView('user.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4','portrait');
        $pdf->setOption("isRemoteEnable", true);
        $pdf->render();

        return $pdf->stream('Data User'.date('Y-m-d H:i:s').'.pdf');
    }

    public function update_photo(Request $request)
     {
         // Validasi file
         $request->validate([
             'photo_profile' => 'required|image|mimes:jpeg,png,jpg|max:2048',
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
             if ($userModel->photo_profile && file_exists(storage_path('app/public/' . $userModel->photo_profile))) {
                 Storage::disk('public')->delete($userModel->photo_profile);
             }
 
             $fileName = 'profile_' . $userId . '_' . time() . '.' . $request->photo_profile->extension();
             $path = $request->photo_profile->storeAs('profiles', $fileName, 'public');
 
             UserModel::where('user_id', $userId)->update([
                 'photo_profile' => $path
             ]);
 
             return redirect()->back()->with('success', 'Foto profile berhasil diperbarui');
         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
         }
     }

     public function delete_photo()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
            }

            $userModel = UserModel::find($user->user_id);

            if (!$userModel || !$userModel->photo_profile) {
                return redirect()->back()->with('error', 'Tidak ada foto yang dapat dihapus');
            }

            if (Storage::disk('public')->exists($userModel->photo_profile)) {
                Storage::disk('public')->delete($userModel->photo_profile);
            }

            $userModel->update(['photo_profile' => null]);

            return redirect()->back()->with('success', 'Foto profil berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }
   
    // public function index(){

    //     $user = UserModel::with('level')->get();
    //     return view('user',['data'=> $user]);

    //     // $user = UserModel::with('level')->get();
    //     // dd($user);

    //     // $user = UserModel::all();
    //     // return view('user', ['data' => $user]); 
    //     // // Kirim ke tampilan

 
 
    //     // $user = UserModel::firstwhere('level_id',1);
    //             // $data = [
    //     //     'level_id' => '2',
    //     //     'username' => 'manager_tiga',
    //     //     'nama' => 'Manager 3',
    //     //     'password' => Hash::make('12345')
    //     // ];

 
    //     // UserModel::create($data);

    //       // $user = UserModel::findOr(20, ['username','nama'], function(){
    //     //     abort(404);
    //     // });

    //      // $user = UserModel::where('username','manager9')->firstOrFail();

    //     //  $user = UserModel::where('level_id', 2)->count(); Hitung jumlah user

    //             // $user = UserModel::firstOrCreate([// otomatis menambah data yang tidak ada 
    //     //     'username' => 'manager22',
    //     //     'nama' => 'Manager Dua Dua',
    //     //     'password' => Hash::make('12345'),
    //     //     'level_id' => '2'
    //     // ],);

    //     // $user = UserModel::firstOrNew([//harus melakukan save untuk menambah data yang tidak ada 
    //     //     'username' => 'manager33',
    //     //     'nama' => 'Manager Tiga Tiga',
    //     //     'password' => Hash::make('12345'),
    //     //     'level_id' => '2'
    //     // ],);
    //     // $user->save();

    //             // $user = UserModel::firstOrCreate([
    //     //     'username' => 'manager55',
    //     //     'nama' => 'Manager55',
    //     //     'password' => Hash::make('12345'),
    //     //     'level_id' => '2'
    //     // ],);

    //     // $user->username = 'manager56';
    //     // $user->isDirty();
    //     // $user->isDirty('username');
    //     // $user->isDirty('nama');
    //     // $user->isDirty(['nama','username']);

    //     // $user->isClean();
    //     // $user->isClean('username');
    //     // $user->isClean('nama');
    //     // $user->isClean(['nama','username']);

    //     // $user->save();

    //     // $user->isDirty();
    //     // $user->isClean();

    //     // dd($user->isDirty());

    //             // $user = UserModel::create([
    //     //     'username' => 'manager11',
    //     //     'nama' => 'Manager11',
    //     //     'password' => Hash::make('12345'),
    //     //     'level_id' => '2'
    //     // ]);

    //     // $user->username = 'manager12';
    //     // $user->save();

    //     // $user->wasChanged();
    //     // $user->wasChanged('username');
    //     // $user->wasChanged('username','level_id');
    //     // $user->wasChanged('nama');
    //     // dd($user->wasChanged('nama','username'));

 
    //     // return view('user', ['data'=>$user]);
    // }

    // public function tambah(){
    //     return view('user_tambah');
    // }

    // public function tambah_simpan(Request $request){
    //     UserModel::create([
    //         'username' => $request->username,
    //         'nama' => $request->nama,
    //         'password' => Hash::make('$request->password'),
    //         'level_id' => $request->level_id
    //     ]);

    //     return redirect('/user');
    // }

    // public function ubah($id){
    //     $user = UserModel::find($id);
    //     return view('user_ubah', ['data' => $user]);
    // }

    // public function ubah_simpan($id, Request $request){
    //     $user = UserModel::find($id);

    //     $user->username = $request->username;
    //     $user->nama = $request->nama;
    //     $user->password = Hash::make('$request->password');
    //     $user->level_id = $request->level_id;

    //     $user->save();

    //     return redirect('/user');
    // }

    // public function hapus($id){
    //     $user = UserModel::find($id);
    //     $user->delete();

    //     return redirect('/user');
    // }

}