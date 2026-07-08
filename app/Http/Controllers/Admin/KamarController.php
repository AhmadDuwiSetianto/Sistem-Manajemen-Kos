<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary; // Kita panggil inti library Cloudinary, bukan Facade Laravel

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

        // BYPASS CACHE: Inisialisasi manual menggunakan env() yang sudah terbukti jalan
        if ($request->hasFile('gambar')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $uploadResult = $cloudinary->uploadApi()->upload($request->file('gambar')->getRealPath(), [
                'folder' => 'kamar_kos'
            ]);

            $data['gambar'] = $uploadResult['secure_url'];
        }

        if ($request->filled('fasilitas')) {
            $fasilitasArray = array_map('trim', explode(',', $request->fasilitas));
            $data['fasilitas'] = json_encode($fasilitasArray);
        } else {
            $data['fasilitas'] = json_encode([]);
        }

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

        // BYPASS CACHE: Hapus gambar lama dan unggah yang baru secara manual
        if ($request->hasFile('gambar')) {
            $this->deleteCloudinaryImage($kamar->gambar);

            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $uploadResult = $cloudinary->uploadApi()->upload($request->file('gambar')->getRealPath(), [
                'folder' => 'kamar_kos'
            ]);

            $data['gambar'] = $uploadResult['secure_url'];
        }

        $fasilitasArray = array_map('trim', explode(',', $request->fasilitas));
        $data['fasilitas'] = json_encode($fasilitasArray);

        $kamar->update($data);

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil diperbarui.');
    }

    /**
     * Hapus kamar
     */
    public function destroy(Kamar $kamar)
    {
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
     * Helper function untuk menghapus gambar dari Cloudinary (Bypass Cache)
     */
    private function deleteCloudinaryImage($imageUrl)
    {
        if (!$imageUrl) {
            return;
        }

        try {
            $path = parse_url($imageUrl, PHP_URL_PATH);
            $parts = explode('/', $path);

            if (count($parts) >= 2) {
                $folder = $parts[count($parts) - 2];
                $file = $parts[count($parts) - 1];
                $filename = pathinfo($file, PATHINFO_FILENAME);

                $publicId = $folder . '/' . $filename;

                // Panggil API Destroy secara manual menggunakan instansiasi baru
                $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
                $cloudinary->uploadApi()->destroy($publicId);
            }
        } catch (\Exception $e) {
            // Abaikan error
        }
    }
}
