<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Apar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import untuk menyimpan file
use RealRashid\SweetAlert\Facades\Alert;

class AparController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apar = Apar::orderBy('nama')->get();

        return view('apar.apar', [
            'apar' => $apar
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); // Retrieve all users
        return view('apar.apar-add', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute harus diisi',
            'min' => ':attribute minimal :min karakter',
            'max' => ':attribute maksimal :max karakter',
            'mimes' => 'file :attribute harus bertipe :mimes',
            'unique' => ':attribute harus unique'
        ];

        $request->validate([
            'nama' => 'required|min:5',
            'kode' => 'required|unique:apars|min:2',
            'lokasi' => 'required|max:20',
            'supplier' => 'required|min:3',
            'media' => 'required|mimes:jpeg,png,jpg,pdf|max:2048', // Maksimal 2MB
            'status' => 'required|in:good,no', // Menambahkan rule in:good,no
            'user_id' => 'required|exists:users,id' // Menambahkan rule exists:users,id
        ], $messages);

        $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
        $filePath = $request->file('media')->storeAs('media', $fileName);

        $apar = new Apar();
        $apar->nama = $request->nama;
        $apar->kode = $request->kode;
        $apar->lokasi = $request->lokasi;
        $apar->supplier = $request->supplier;
        $apar->media = $fileName; // Simpan nama file
        $apar->status = $request->status;
        $apar->user_id = $request->user_id; // Ambil user_id dari form
        $apar->save();

        Alert::success('Success', 'Apar has been added');

        return redirect('/apar');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $apar = Apar::findOrFail($id);

        return view('apar.show', [
            'apar' => $apar
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $apar = Apar::findOrFail($id);

        return view('apar.apar-edit', [
            'apar' => $apar
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'kode' => 'required|unique:apars,kode,'. $id,
            'lokasi' => 'required',
            'status' => 'required|in:good,no', // Menambahkan rule in:good,no
        ]);

        $apar = Apar::findOrFail($id);
        $apar->nama = $request->nama;
        $apar->kode = $request->kode;
        $apar->lokasi = $request->lokasi;
        $apar->status = $request->status;
        $apar->save();

        Alert::success('Success', 'Apar has been updated');

        return redirect('/apar');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $apar = Apar::findOrFail($id);
        $apar->delete();

        Alert::success('Success', 'Apar has been deleted');

        return redirect('/apar');
    }
}
