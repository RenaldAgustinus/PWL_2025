<?php

namespace App\Http\Controllers; // menunjukkan file ini terletak di folder app/Http\Controllers

use App\Models\Item; //Menggunakan model Item untuk menangani input dari pengguna.
use Illuminate\Http\Request; //Menggunakan class Request untuk menangani input dari pengguna.

class ItemController extends Controller //Mendefinisikan class ItemController yang mewarisi Controller
{
    public function index() //method index
    {
        $items = Item::all();//Mengambil semua data dari tabel items
        return view('items.index', compact('items')); //Mengembalikan view items.index dengan data items
    }

    public function create() //method create
    {
        return view('items.create');// Menampilkan form untuk menambahkan item baru.
    }

    public function store(Request $request) //method store
    {
        $request->validate([ // Validasi request dari form
            'name' => 'required',
            'description' => 'required',
        ]);
         
        //Item::create($request->all());
        //return redirect()->route('items.index');

        // Hanya masukkan atribut yang diizinkan
         Item::create($request->only(['name', 'description']));
        return redirect()->route('items.index')->with('success', 'Item added successfully.');//Menyimpan data yang diizinkan (name dan description), lalu diarahkan kembali ke index
    }

    public function show(Item $item) //method show
    {
        return view('items.show', compact('item'));// Menampilkan detail dari item tertentu berdasarkan ID
    }

    public function edit(Item $item) //method edit
    {
        return view('items.edit', compact('item')); //Menampilkan form untuk mengedit item tertentu.
    }

    public function update(Request $request, Item $item) //method update
    {
        $request->validate([ //Memperbarui data item yang telah diedit
            'name' => 'required',
            'description' => 'required',
        ]);
         
        //$item->update($request->all());
        //return redirect()->route('items.index');
        // Hanya masukkan atribut yang diizinkan
         $item->update($request->only(['name', 'description']));
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');  // Mengembalikan pada route items.index dan menambahkan pesan sukse
    }

    public function destroy(Item $item) //method destroy
    {
        
       // return redirect()->route('items.index');
       $item->delete(); //menghapus item
       return redirect()->route('items.index')->with('success', 'Item deleted successfully.'); // Mengembalikan pada route items.index dan menambahkan pesan sukses
    }
}
