<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md text-center">
        <h2 class="text-2xl font-bold text-blue-600 mb-4">📬 Cek Email Kamu</h2>
        <p class="text-gray-700 mb-4">Kami telah mengirimkan email verifikasi ke alamat yang kamu daftarkan.</p>
        <p class="text-gray-600">Silakan klik link verifikasi pada email tersebut.</p>

        <div class="flex justify-center mt-6">
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
            </svg>
        </div>

        <p class="mt-4 text-sm text-gray-500">Menunggu verifikasi email...</p>
    </div>

    <script>
        setInterval(() => {
            fetch('/check-verification', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.verified) {
                    window.location.href = '/login';
                }
            });
        }, 5000); // Cek setiap 5 detik
    </script>

</body>
</html>
