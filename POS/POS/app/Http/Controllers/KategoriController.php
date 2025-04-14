<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object)[
            'title' => 'Daftar Kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif
 
        $kategori = KategoriModel::all();

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'activeMenu' => $activeMenu, 'kategori' => $kategori]);
    }

    public function list(Request $request){
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
    
        return DataTables::of($kategoris)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                // $btn = '<a href="'.url('/kategori/' . $kategori->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'.url('/kategori/' . $kategori->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/kategori/' . $kategori->kategori_id).'">';
                // $btn .= csrf_field() . method_field("DELETE");
                // $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>';
                // $btn .= '</form>';
                // return $btn;

                $btn  = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
    
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori';

        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request){
        $request->validate([
            'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|min:5',
        ]);

        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('Success', 'Data berhasil disimpan');
    }
    public function show(string $id){
        $user = KategoriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.show',['user' => $user, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    public function edit(string $id){
        $user = KategoriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Kategori',
            'list' => ['Home', 'kategori', 'Edit'],
        ];

        $page = (object)[
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }
    public function update(Request $request, string $id){
        $request->validate([
            'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|min:5',
        ]);

        KategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('Success', 'Data berhasil disimpan');
    }
    public function destroy(string $id){
        $check = KategoriModel::find($id);

        if (!$check) { // cek data ada atau tidak
            return redirect('/kategori')->with('error','data tidak ditemukan');
        }

        try{
            KategoriModel::destroy($id);// hapus data levelS
            return redirect('/kategori')->with('success','Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e){
            return redirect('/kategori')->with('error', 'Data user gagal dihapus karena terdapat tabel lain yang masih terkait dengan data ini');
        }
    }

    public function create_ajax(){
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request){
        //cek req ajax
        if ($request->ajax()|| $request->wantsJson()) {
            $rules =[
                'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string',
            ]; 
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, //response status
                    'message' => 'Validasi Gagal', //pesan gagal
                    'msgField' => $validator->errors(), //pesan error
                ]);
            }
            KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $kategori = KategoriModel::find($id);
        return view('kategori.edit_ajax',['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id){       
    // Cek apakah request berasal dari AJAX
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode,'.$id.',kategori_id',
            'kategori_nama' => 'required|string',
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
        $check = KategoriModel::find($id);
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
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, $id){
        if ($request->ajax() || $request->wantsJson()) {
            $user = KategoriModel::find($id);
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
        return view('kategori.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_kategori'); 
    
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
                            'kategori_kode' => $value['A'],
                            'kategori_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    KategoriModel::insertOrIgnore($insert);
                }
    
                return response()->json([
                    'status'  => true,
                    'message' => 'Data kategori berhasil diimport'
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
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')
                                 ->orderBy('kategori_kode')
                                 ->get();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); 
    
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kategori');
        $sheet->setCellValue('C1', 'Nama Kategori');
    
        $sheet->getStyle('A1:C1')->getFont()->setBold(true); 
    
        $no = 1; 
        $baris = 2; 
        foreach ($kategori as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->kategori_kode);
            $sheet->setCellValue('C'.$baris, $value->kategori_nama);
            $baris++;
            $no++;
        }
        
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);          
        }
    
        $sheet->setTitle('Data Kategori'); 
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori '.date('Y-m-d H:i:s').'.xlsx';
        
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
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama')
                    ->orderBy('kategori_id')
                    ->get();

        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('a4','portrait');
        $pdf->setOption("isRemoteEnable", true);
        $pdf->render();

        return $pdf->stream('Data Kategori'.date('Y-m-d H:i:s').'.pdf');
    }
    
    // public function index(){
    //     // $data =[
    //     //     'kategori_kode' => 'SNK',
    //     //     'kategori_nama' => 'Snake/Makanan Ringan',
    //     //     'created_at' => now()
    //     // ];

    //     // DB::table('m_kategori')->insert($data);
    //     // return 'Insert data baru berhasil';

    //     // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
    //     // return 'update data berhasil. Jumlah data yang di update :'.$row.' baris';

    //     // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
    //     // return 'Delete berhasil. Jumlah data yang di hapus '.$row.' Baris';

    //     $data = DB::table('m_kategori')->get();
    //     return view('kategori', ['data'=>$data]);
    // }
}