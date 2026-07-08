<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; // Tambahkan import Facade ini agar IDE tidak error

class KamarController extends Controller
{
    /**
     * Tampilkan daftar kamar
     */
    public function index()
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();

        $kamars = Kamar::latest()->get();
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
     * Simpan data kamar baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nomor_kamar' => 'required|unique:kamar,nomor_kamar',
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
            'nomor_kamar',
            'tipe_kamar',
            'harga',
            'ukuran',
            'lantai',
            'kapasitas',
            'status',
            'deskripsi'
        ]);

        $data['is_active'] = $request->has('is_active');

        // 3. Handle Gambar (Upload ke Cloudinary menggunakan Facade)
        if ($request->hasFile('gambar')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'folder' => 'kamar_kos'
            ])->getSecurePath();

            $data['gambar'] = $uploadedFileUrl;
        }

        // 4. Handle Fasilitas 
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
            'nomor_kamar' => 'required|unique:kamar,nomor_kamar,' . $kamar->id,
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
            'nomor_kamar',
            'tipe_kamar',
            'harga',
            'ukuran',
            'lantai',
            'kapasitas',
            'status',
            'deskripsi'
        ]);

        $data['is_active'] = $request->has('is_active');

        // 3. Handle Gambar (Hapus lama dari Cloudinary, Upload baru)
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            $this->deleteCloudinaryImage($kamar->gambar);

            // Upload gambar baru menggunakan Facade
            $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'folder' => 'kamar_kos'
            ])->getSecurePath();

            $data['gambar'] = $uploadedFileUrl;
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
        // Hapus gambar dari Cloudinary sebelum data dihapus dari database
        $this->deleteCloudinaryImage($kamar->gambar);

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

    /**
     * Helper function untuk menghapus gambar dari Cloudinary
     */
    private function deleteCloudinaryImage($imageUrl)
    {
        if (!$imageUrl) {
            return;
        }

        try {
            // Mengambil nama file (Public ID) dari URL Cloudinary
            $path = parse_url($imageUrl, PHP_URL_PATH);
            $parts = explode('/', $path);

            if (count($parts) >= 2) {
                $folder = $parts[count($parts) - 2];
                $file = $parts[count($parts) - 1];
                $filename = pathinfo($file, PATHINFO_FILENAME);

                $publicId = $folder . '/' . $filename;

                // Eksekusi perintah hapus menggunakan Facade resmi
                Cloudinary::uploadApi()->destroy($publicId);
            }
        } catch (\Exception $e) {
            // Abaikan jika tidak ditemukan agar flow program utama tidak crash
        }
    }
}
