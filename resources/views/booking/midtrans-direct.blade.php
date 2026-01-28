<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memproses Pembayaran...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script type="text/javascript"
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-3xl shadow-xl text-center max-w-sm w-full mx-4 border border-slate-100">
        <div class="w-16 h-16 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin mx-auto mb-6"></div>
        <h2 class="text-xl font-bold text-slate-800 mb-2">Menyiapkan Pembayaran</h2>
        <p class="text-slate-500 text-sm mb-6">Harap tunggu, popup pembayaran akan segera muncul...</p>
        
        <button onclick="pay()" class="text-blue-600 font-bold text-sm hover:underline">
            Klik jika popup tidak muncul
        </button>
    </div>

    <script type="text/javascript">
        function pay() {
            // Cek apakah snap token ada
            var snapToken = '{{ $pembayaran->snap_token }}';
            
            if (!snapToken) {
                alert('Token pembayaran belum siap. Halaman akan dimuat ulang.');
                window.location.href = "{{ route('booking.payment', $pembayaran->id) }}";
                return;
            }

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    window.location.href = "{{ route('booking.receipt', $pembayaran->id) }}";
                },
                onPending: function(result) {
                    window.location.href = "{{ route('booking.payment', $pembayaran->id) }}";
                },
                onError: function(result) {
                    // Redirect kembali ke halaman payment agar bisa retry/cancel
                    window.location.href = "{{ route('booking.payment', $pembayaran->id) }}?error=true";
                },
                onClose: function() {
                    window.location.href = "{{ route('booking.payment', $pembayaran->id) }}";
                }
            });
        }
        
        // Auto trigger dengan sedikit delay untuk memastikan script load
        setTimeout(pay, 500);
    </script>
</body>
</html>