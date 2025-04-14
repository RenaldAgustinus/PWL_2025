<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Monolog\Level;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class LevelController extends Controller
{
        public function index()
        {
            $breadcrumb = (object)[
                'title' => 'Daftar Level',
                'list' => ['Home', 'Level']
            ];
    
            $page = (object)[
                'title' => 'Daftar Level yang terdaftar dalam sistem'
            ];
    
            $activeMenu = 'level'; // set menu yang sedang aktif

            $level = LevelModel::all();
    
            return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'activeMenu' => $activeMenu, 'level' =>$level]);
    }

    public function list(Request $request) {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');
    
        return DataTables::of($levels)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                // $btn = '<a href="'.url('/level/' . $level->level_id).'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'.url('/level/' . $level->level_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/level/' . $level->level_id).'">';
                // $btn .= csrf_field() . method_field("DELETE");
                // $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>';
                // $btn .= '</form>';
                // return $btn;
                $btn  = '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
    
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah level baru'
        ];

        $activeMenu = 'level';

        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request){
        $request->validate([
            'level_kode'=> 'required|string|unique:m_level',
            'level_nama'=> 'required|min:5',
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,// enkripsi pass
        ]);

        return redirect('/level')->with('Success', 'Data bDerhasil disimpan');
    }

    public function show(string $id){
        $user = LevelModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail level'
        ];

        $activeMenu = 'user';

        return view('level.show',['user' => $user, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id){
        $user = LevelModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit'],
        ];

        $page = (object)[
            'title' => 'Edit level'
        ];

        $activeMenu = 'level';

        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'level_kode'=> 'required|string',
            'level_nama'=> 'required|min:5',
        ]);

        LevelModel::find($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('Success', 'Data berhasil disimpan');
    }

    public function destroy(string $id){
        $check = LevelModel::find($id);

        if (!$check) { // cek data ada atau tidak
            return redirect('/level')->with('error','data tidak ditemukan');
        }

        try{
            LevelModel::destroy($id);// hapus data level
            return redirect('/level')->with('success','Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e){
            return redirect('/level')->with('error', 'Data user gagal dihapus karena terdapat tabel lain yang masih terkait dengan data ini');
        }
    }


    public function create_ajax(){
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request){
        //cek req ajax
        if ($request->ajax()|| $request->wantsJson()) {
            $rules =[
                'level_kode' => 'required|string|unique:m_level,level_kode',
                'level_nama' => 'required|string',
            ]; 
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, //response status
                    'message' => 'Validasi Gagal', //pesan gagal
                    'msgField' => $validator->errors(), //pesan error
                ]);
            }
            LevelModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $level = LevelModel::find($id);
        return view('level.edit_ajax',['level' => $level]);
    }

    public function update_ajax(Request $request, $id){       
    // Cek apakah request berasal dari AJAX
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_kode' => 'required|string|unique:m_level,level_kode,'.$id.',level_id',
            'level_nama' => 'required|string',
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
        $check = LevelModel::find($id);
        if ($check) {

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
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id){
        if ($request->ajax() || $request->wantsJson()) {
            $user = LevelModel::find($id);
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
        return view('level.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_level' => ['required', 'mimes:xlsx', 'max:1024']
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
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
                    if ($baris > 1) { 
                        $insert[] = [
                            'level_kode' => $value['A'],
                            'level_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    LevelModel::insertOrIgnore($insert);
                }
    
                return response()->json([
                    'status'  => true,
                    'message' => 'Data level berhasil diimport'
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
        $level = LevelModel::select('level_kode', 'level_nama')
                           ->orderBy('level_kode')
                           ->get();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); 
    
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Level');
        $sheet->setCellValue('C1', 'Nama Level');
    
        $sheet->getStyle('A1:C1')->getFont()->setBold(true); 
    
        $no = 1; 
        $baris = 2; 
        foreach ($level as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->level_kode);
            $sheet->setCellValue('C'.$baris, $value->level_nama);
            $baris++;
            $no++;
        }
        
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);        
        }
    
        $sheet->setTitle('Data Level'); 
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Level '.date('Y-m-d H:i:s').'.xlsx';
        
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
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama')
                    ->orderBy('level_id')
                    ->get();

        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4','portrait');
        $pdf->setOption("isRemoteEnable", true);
        $pdf->render();

        return $pdf->stream('Data Level'.date('Y-m-d H:i:s').'.pdf');
    }
    // public function index(){
    //     // DB::insert('insert into m_level(level_kode, level_nama, created_at) values(?,?,?)', ['CUS', 'Pelanggan', now()]);
    //     // return 'Insert data baru berhasil';

    //     // $row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
    //     // return 'Update data berhasil. Jumlah data yang diupdate'. $row .' Baris';

    //     // $row = DB::delete('delete from m_level where level_kode = ?',['CUS']);
    //     // return 'Delete data berhasil. Jumlah data yang dihapus '.$row.' Baris';

    //     // $data = DB::select('select * from m_level');
    //     // return view('level', ['data' => $data]);
    // }
}