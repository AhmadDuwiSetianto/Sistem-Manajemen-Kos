<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking; // Import Booking model
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    /**
     * Tampilkan daftar kamar
     */
    public function index()
    {
        // Hitung booking aktif untuk badge di sidebar/header jika perlu
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        
        $kamars = Kamar::latest()->get(); // Use get() or paginate()
        return view('admin.kamar.index', compact('kamars', 'activeBooking'));
    }

    /**
     * Tampilkan form tambah kamar
     */
    public function create()
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        return view('admin.kamar.create', compact('activeBooking'));
    }

    /**
     * 🔥 FUNGSI INI YANG HILANG SEBELUMNYA (STORE) 🔥
     * Simpan data kamar baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nomor_kamar' => 'required|unique:kamar,nomor_kamar', // Correct table name 'kamar' based on your model
            'tipe_kamar'  => 'required|in:Standard,Deluxe,Executive,Superior,VIP',
            'harga'       => 'required|numeric|min:0',
            'ukuran'      => 'nullable|integer|min:0',
            'lantai'      => 'nullable|integer|min:1',
            'kapasitas'   => 'nullable|integer|min:1',
            'fasilitas'   => 'required', // String dipisah koma
            'status'      => 'required|in:tersedia,terisi,maintenance',
            'deskripsi'   => 'nullable|string|max:1000',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Ambil Data
        $data = $request->only([
            'nomor_kamar', 'tipe_kamar', 'harga', 'ukuran', 
            'lantai', 'kapasitas', 'status', 'deskripsi'
        ]);

        $data['is_active'] = $request->has('is_active');

        // 3. Handle Gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('kamar', 'public');
        }

        // 4. Handle Fasilitas (String "AC, WiFi" -> JSON ["AC", "WiFi"])
        if ($request->filled('fasilitas')) {
            $fasilitasArray = array_map('trim', explode(',', $request->fasilitas));
            $data['fasilitas'] = json_encode($fasilitasArray);
        } else {
            $data['fasilitas'] = json_encode([]);
        }

        // 5. Simpan
        Kamar::create($data);

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kamar
     */
    public function edit(Kamar $kamar)
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        return view('admin.kamar.edit', compact('kamar', 'activeBooking'));
    }

    /**
     * Update data kamar
     */
    public function update(Request $request, Kamar $kamar)
    {
        // 1. Validasi
        $request->validate([
            'nomor_kamar' => 'required|unique:kamar,nomor_kamar,' . $kamar->id, // Correct table name 'kamar'
            'tipe_kamar'  => 'required|in:Standard,Deluxe,Executive,Superior,VIP',
            'harga'       => 'required|numeric|min:0',
            'ukuran'      => 'nullable|integer|min:0',
            'lantai'      => 'nullable|integer|min:1',
            'kapasitas'   => 'nullable|integer|min:1',
            'fasilitas'   => 'required',
            'status'      => 'required|in:tersedia,terisi,maintenance',
            'deskripsi'   => 'nullable|string|max:1000',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Ambil Data
        $data = $request->only([
            'nomor_kamar', 'tipe_kamar', 'harga', 'ukuran', 
            'lantai', 'kapasitas', 'status', 'deskripsi'
        ]);

        $data['is_active'] = $request->has('is_active');

        // 3. Handle Gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($kamar->gambar && Storage::disk('public')->exists($kamar->gambar)) {
                Storage::disk('public')->delete($kamar->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('kamar', 'public');
        }

        // 4. Handle Fasilitas
        $fasilitasArray = array_map('trim', explode(',', $request->fasilitas));
        $data['fasilitas'] = json_encode($fasilitasArray);

        // 5. Update
        $kamar->update($data);

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil diperbarui.');
    }

    /**
     * Hapus kamar
     */
    public function destroy(Kamar $kamar)
    {
        if ($kamar->gambar && Storage::disk('public')->exists($kamar->gambar)) {
            Storage::disk('public')->delete($kamar->gambar);
        }

        $kamar->delete();

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif kamar
     */
    public function toggleStatus(Kamar $kamar)
    {
        $kamar->update(['is_active' => !$kamar->is_active]);

        $status = $kamar->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Kamar berhasil $status.");
    }
}