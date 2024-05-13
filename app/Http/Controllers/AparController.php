<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Apar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;
use Milon\Barcode\Facades\DNS1D;

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
            'lokasi' => 'required|max:20',
            'supplier' => 'required|min:3',
            'media' => 'required|mimes:jpeg,png,jpg,pdf|max:2048', // Maksimal 2MB
            'status' => 'required|in:good,no', // Menambahkan rule in:good,no
            'user_id' => 'required|exists:users,id' // Menambahkan rule exists:users,id
        ], $messages);

        // Simpan file media
        $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
        $filePath = $request->file('media')->storeAs('media', $fileName, 'public');

        // Simpan data Apar
        $apar = new Apar();
        $apar->nama = $request->nama;
        $apar->kode = uniqid(); // Menghasilkan kode unik secara otomatis
        $apar->lokasi = $request->lokasi;
        $apar->supplier = $request->supplier;
        $apar->media = $fileName; // Simpan nama file
        $apar->status = $request->status;
        $apar->user_id = $request->user_id; // Ambil user_id dari form


        $apar->save();

        Alert::success('Success', 'Apar has been added');

        return redirect('/apar')->with('messages', $messages);
    }

    public function generate($id)
    {
        $apar = Apar::findOrFail($id);
        $qrcode = QrCode::size(400)->generate($apar->nama);
        return view('apar.qrcode', compact('qrcode'));
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
        $apar = Apar::findOrFail($id); // Ambil data Apar berdasarkan ID
        $users = User::all(); // Retrieve all users
        return view('apar.apar-edit', compact('users', 'apar'));
        ////////
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
            'lokasi' => 'required|max:20',
            'supplier' => 'required|min:3',
            'media' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048', // Maksimal 2MB
            'status' => 'required|in:good,no', // Menambahkan rule in:good,no
            'user_id' => 'required|exists:users,id' // Menambahkan rule exists:users,id
        ], $messages);

        $apar = Apar::findOrFail($id);

        if (!$apar) {
            return redirect()->route('apar.apar')->with('error', 'Apar tidak ditemukan.');
        }

        $apar->nama = $request->nama;
        $apar->lokasi = $request->lokasi;
        $apar->supplier = $request->supplier;
        $apar->status = $request->status;
        $apar->user_id = $request->user_id; // Ambil user_id dari form

        if ($request->hasFile('media')) {
            $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
            $filePath = $request->file('media')->storeAs('media', $fileName, 'public');

            // Delete previous media file if exists
            Storage::disk('public')->delete('media/' . $apar->media);

            $apar->media = $fileName; // Simpan nama file
        }

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
