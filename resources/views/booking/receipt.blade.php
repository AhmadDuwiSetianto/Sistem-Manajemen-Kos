@extends('layouts.app')

@section('title', 'Bukti Pembayaran #' . $pembayaran->kode_pembayaran)

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* CSS Khusus Print/PDF */
    @media print {
        body * { visibility: hidden; }
        #printable-area, #printable-area * { visibility: visible; }
        #printable-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        .bg-slate-50 { background-color: white !important; }
    }
</style>

<div class="bg-slate-50 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 no-print gap-4">
            <a href="{{ route('home') }}" class="flex items-center text-slate-500 hover:text-blue-600 transition text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>

            <div class="flex gap-3">
                <a href="{{ route('user.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-semibold shadow-md transition flex items-center gap-2 text-sm">
                    <i class="fas fa-tachometer-alt"></i> Dashboard Saya
                </a>

                <button onclick="downloadPDF()" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-lg font-semibold shadow-md transition flex items-center gap-2 text-sm">
                    <i class="fas fa-file-download"></i> Download PDF
                </button>

                <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-5 py-2 rounded-lg font-semibold shadow-sm transition flex items-center gap-2 text-sm">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <div id="printable-area" class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200 relative">
            
            <div class="absolute top-10 right-10 opacity-10 pointer-events-none transform -rotate-12">
                <span class="text-8xl font-black text-green-600 border-8 border-green-600 px-8 py-2 rounded-xl tracking-widest">PAID</span>
            </div>

            <div class="bg-slate-900 text-white p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-xl">M</div>
                            <span class="text-2xl font-bold tracking-tight">MyKos</span>
                        </div>
                        <p class="text-slate-400 text-sm">Jl. Contoh No. 123, Pekalongan<br>Jawa Tengah, Indonesia<br>support@mykos.com</p>
                    </div>
                    <div class="text-right">
                        <h1 class="text-3xl font-bold mb-1">INVOICE</h1>
                        <p class="text-blue-400 font-mono text-lg">#{{ $pembayaran->kode_pembayaran }}</p>
                        <div class="mt-4 inline-block bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            LUNAS / PAID
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-2 gap-8 mb-8 border-b border-slate-100 pb-8">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ditagihkan Kepada</h3>
                        <p class="text-lg font-bold text-slate-800">{{ $pembayaran->user->name }}</p>
                        <p class="text-slate-500 text-sm">{{ $pembayaran->user->email }}</p>
                        <p class="text-slate-500 text-sm">{{ $pembayaran->user->no_telepon ?? $pembayaran->user->phone }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Detail Pembayaran</h3>
                        <div class="space-y-1">
                            <p class="text-sm text-slate-600">
                                <span class="text-slate-400 mr-2">Tanggal Bayar:</span> 
                                {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d F Y, H:i') }}
                            </p>
                            
                            <p class="text-sm text-slate-600">
                                <span class="text-slate-400 mr-2">Metode:</span> 
                                <span class="font-semibold text-slate-800">
                                    @php
                                        $method = $pembayaran->metode_pembayaran;
                                        // Normalisasi string (hilangkan underscore dll)
                                        if (str_contains($method, 'bank_transfer')) {
                                            echo 'Transfer Bank (Virtual Account)';
                                        } elseif (str_contains($method, 'bca')) {
                                            echo 'BCA Virtual Account';
                                        } elseif (str_contains($method, 'bni')) {
                                            echo 'BNI Virtual Account';
                                        } elseif (str_contains($method, 'bri')) {
                                            echo 'BRI Virtual Account';
                                        } elseif (str_contains($method, 'mandiri')) {
                                            echo 'Mandiri Bill';
                                        } elseif (str_contains($method, 'gopay') || str_contains($method, 'qris')) {
                                            echo 'QRIS / GoPay';
                                        } elseif (str_contains($method, 'shopeepay')) {
                                            echo 'ShopeePay';
                                        } elseif (str_contains($method, 'cstore')) {
                                            echo 'Indomaret / Alfamart';
                                        } else {
                                            // Fallback jika nama aneh, ubah underscore jadi spasi
                                            echo ucwords(str_replace('_', ' ', $method ?? 'Online Payment'));
                                        }
                                    @endphp
                                </span>
                            </p>
                            </div>
                    </div>
                </div>

                <div class="mb-8">
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="pb-4 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200">Deskripsi</th>
                                <th class="pb-4 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200 text-center">Durasi</th>
                                <th class="pb-4 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200 text-right">Harga</th>
                                <th class="pb-4 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-600">
                            <tr>
                                <td class="py-4 border-b border-slate-100">
                                    <p class="font-bold text-slate-800 text-base">Sewa Kamar {{ $pembayaran->booking->kamar->nomor_kamar }}</p>
                                    <p class="text-xs text-slate-500">Tipe {{ ucfirst($pembayaran->booking->kamar->tipe_kamar) }}</p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        Check-in: {{ \Carbon\Carbon::parse($pembayaran->booking->tanggal_masuk)->translatedFormat('d M Y') }}
                                    </p>
                                </td>
                                <td class="py-4 border-b border-slate-100 text-center">
                                    {{ $pembayaran->booking->durasi }} Bulan
                                </td>
                                <td class="py-4 border-b border-slate-100 text-right">
                                    Rp {{ number_format($pembayaran->booking->kamar->harga, 0, ',', '.') }}
                                </td>
                                <td class="py-4 border-b border-slate-100 text-right font-medium text-slate-800">
                                    Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mb-8">
                    <div class="w-full sm:w-1/2 lg:w-1/3">
                        <div class="flex justify-between mb-2 text-sm text-slate-500">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mb-2 text-sm text-slate-500">
                            <span>Biaya Admin</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="border-t border-slate-200 mt-4 pt-4 flex justify-between items-center">
                            <span class="font-bold text-slate-800 text-lg">Total Bayar</span>
                            <span class="font-bold text-blue-600 text-2xl">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-6 text-center border border-slate-100">
                    <p class="text-slate-600 font-medium mb-1">Terima kasih atas pembayaran Anda!</p>
                    <p class="text-xs text-slate-400">Simpan bukti pembayaran ini sebagai referensi yang sah.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-8 no-print">
            <p class="text-sm text-slate-500">Butuh bantuan? <a href="#" class="text-blue-600 hover:underline">Hubungi Admin</a></p>
        </div>

    </div>
</div>

<script>
    function downloadPDF() {
        // Ambil elemen yang ingin dicetak
        var element = document.getElementById('printable-area');
        
        // Konfigurasi PDF
        var opt = {
            margin:       [10, 10, 10, 10], // Margin (atas, kiri, bawah, kanan)
            filename:     'Invoice-{{ $pembayaran->kode_pembayaran }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true }, // Scale 2 agar teks tajam
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Eksekusi Download
        // Menampilkan loading sederhana (opsional)
        var btn = document.querySelector('button[onclick="downloadPDF()"]');
        var originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        html2pdf().set(opt).from(element).save().then(function(){
            // Kembalikan tombol seperti semula setelah download selesai
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endsection