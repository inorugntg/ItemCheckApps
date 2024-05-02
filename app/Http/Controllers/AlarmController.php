<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alarm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import untuk menyimpan file
use RealRashid\SweetAlert\Facades\Alert;

class AlarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alarm = Alarm::orderBy('nama')->get();

        return view('alarm.alarm', [
            'alarm' => $alarm
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); // Retrieve all users
        return view('alarm.alarm-add', compact('users'));
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
            'kode' => 'required|unique:alarms|min:2',
            'lokasi' => 'required|max:20',
            'supplier' => 'required|min:3',
            'media' => 'required|mimes:jpeg,png,jpg,pdf|max:2048', // Maksimal 2MB
            'status' => 'required|in:good,no', // Menambahkan rule in:good,no
            'user_id' => 'required|exists:users,id' // Menambahkan rule exists:users,id
        ], $messages);

        $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
        $filePath = $request->file('media')->storeAs('media', $fileName);

        $alarm = new Alarm();
        $alarm->nama = $request->nama;
        $alarm->kode = uniqid(); // Menghasilkan kode unik secara otomatis
        $alarm->lokasi = $request->lokasi;
        $alarm->supplier = $request->supplier;
        $alarm->media = $fileName; // Simpan nama file
        $alarm->status = $request->status;
        $alarm->user_id = $request->user_id; // Ambil user_id dari form
        $alarm->save();

        Alert::success('Success', 'Alarm has been added');

        return redirect('/alarm');
    }

    public function generate($id)
    {
        $alarm = Alarm::findOrFail($id);
        $qrcode = QrCode::size(400)->generate($alarm->nama);
        return view('alarm.qrcode', compact('qrcode'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $alarm = Alarm::findOrFail($id);

        return view('alarm.show', [
            'alarm' => $alarm
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $alarm = Alarm::findOrFail($id); // Ambil data Apar berdasarkan ID
        $users = User::all(); // Retrieve all users
        return view('alarm.alarm-edit', compact('users','alarm'));
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

        $alarm = Alarm::findOrFail($id);

        if (!$alarm) {
            return redirect()->route('alarm.alarm')->with('error', 'Alarm tidak ditemukan.');
        }

        $alarm->nama = $request->nama;
        $alarm->kode = $request->kode;
        $alarm->lokasi = $request->lokasi;
        $alarm->status = $request->status;

        if ($request->hasFile('media')) {
            $fileName = time() . '_' . $request->file('media')->getClientOriginalName();
            $filePath = $request->file('media')->storeAs('media', $fileName, 'public');

            // Delete previous media file if exists
            Storage::disk('public')->delete('media/' . $alarm->media);

            $alarm->media = $fileName;
        }

        $alarm->save();

        Alert::success('Success', 'Alarm has been updated');

        return redirect('/alarm');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alarm = Alarm::findOrFail($id);
        $alarm->delete();

        Alert::success('Success', 'Alarm has been deleted');

        return redirect('/alarm');
    }
}
