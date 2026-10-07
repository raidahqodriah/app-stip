<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Layanan Terpadu (SILT-STIP) Sekolah Tinggi Ilmu Pelayaran Jakarta. Portal terintegrasi peminjaman simulator, pengelolaan BMN, rumah dinas, dan perpustakaan maritim.">
    <title>{{ $title ?? 'SILT-STIP' }} | Sistem Informasi Layanan Terpadu STIP Jakarta</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface font-sans text-body-md text-on-surface antialiased flex flex-col min-h-screen">
    <livewire:front.components.navbar />

    <main class="w-full pt-20 bg-canvas-bg min-h-screen flex-grow">
        {{ $slot }}
    </main>

    <livewire:front.components.footer />

    @livewireScripts
</body>
</html>
