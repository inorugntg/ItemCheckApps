<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Hydrant;
use Illuminate\Support\Facades\Storage; // Import untuk menyimpan file
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class HydrantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hydrant = Hydrant::orderBy('nama')->get();

        return view('hydrant.hydrant', [
            'hydrant' => $hydrant
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); // Retrieve all users
        return view('hydrant.hydrant-add', compact('users'));
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
            'kode' => 'required|unique:hydrants|min:2',
            'lokasi' => 'required|max:20',
            'supplier' => 'required|min:3',
            'media' => 'required|mimes:jpeg,png,jpg,pdf|max:2048', // Maksimal 2MB
            'status' => 'required|in:good,no', // Menambahkan rule in:good,no
            'user_id' => 'required|exists:users,id' // Menambahkan rule exists:users,id
        ], $messages);

        $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
        $filePath = $request->file('media')->storeAs('media', $fileName);

        $hydrant = new Hydrant();
        $hydrant->nama = $request->nama;
        $hydrant->kode = $request->kode;
        $hydrant->lokasi = $request->lokasi;
        $hydrant->supplier = $request->supplier;
        $hydrant->media = $fileName; // Simpan nama file
        $hydrant->status = $request->status;
        $hydrant->user_id = $request->user_id; // Ambil user_id dari form
        $hydrant->save();

        Alert::success('Success', 'Hydrant has been added');

        return redirect('/hydrant');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hydrant = Hydrant::findOrFail($id);

        return view('hydrant.show', [
            'hydrant' => $hydrant
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hydrant = Hydrant::findOrFail($id); // Ambil data Apar berdasarkan ID
        $users = User::all(); // Retrieve all users
        return view('hydrant.hydrant-edit', compact('users','hydrant'));
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
            'kode' => 'required|min:2',
            'lokasi' => 'required|max:20',
            'supplier' => 'required|min:3',
            'media' => 'required|mimes:jpeg,png,jpg,pdf|max:2048', // Maksimal 2MB
            'status' => 'required|in:good,no', // Menambahkan rule in:good,no
            'user_id' => 'required|exists:users,id' // Menambahkan rule exists:users,id
        ], $messages);

        $hydrant = Hydrant::findOrFail($id);

        if (!$hydrant) {
            return redirect()->route('hydrant.hydrant')->with('error', 'Hydrant tidak ditemukan.');
        }

        $hydrant->nama = $request->nama;
        $hydrant->kode = $request->kode;
        $hydrant->lokasi = $request->lokasi;
        $hydrant->status = $request->status;

        if ($request->hasFile('media')) {
            $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
            $filePath = $request->file('media')->storeAs('media', $fileName, 'public');

            // Delete previous media file if exists
            Storage::disk('public')->delete('media/' . $hydrant->media);

            $hydrant->media = $fileName;
        }

        $hydrant->save();

        Alert::success('Success', 'Hydrant has been updated');

        return redirect('/hydrant');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hydrant = Hydrant::findOrFail($id);
        $hydrant->delete();

        Alert::success('Success', 'Hydrant has been deleted');

        return redirect('/hydrant');
    }
}
