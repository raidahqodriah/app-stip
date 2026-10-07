<?php

return [
    'brand' => [
        'admin' => 'SILT-STIP (Admin & Pegawai)',
        'student' => 'SILT-STIP (Portal Taruna)',
    ],

    'clusters' => [
        'lab' => [
            'name' => 'Layanan Lab / SPP',
            'breadcrumb' => 'Lab / SPP',
        ],
        'bmn' => [
            'name' => 'BMN & Rumah Dinas',
            'breadcrumb' => 'BMN & Rumah Dinas',
        ],
        'library' => [
            'name' => 'Perpustakaan',
            'breadcrumb' => 'Perpustakaan',
        ],
        'master' => [
            'name' => 'Master & Pengaturan',
            'breadcrumb' => 'Master Data',
        ],
    ],

    'resources' => [
        'audit_logs' => [
            'label' => 'Log Audit',
            'plural_label' => 'Log Audit',
            'navigation_label' => 'Log Audit',
        ],
        'blackout_dates' => [
            'label' => 'Jadwal Blackout',
            'plural_label' => 'Jadwal Libur & Blackout',
            'navigation_label' => 'Jadwal Libur & Blackout',
        ],
        'bmn_items' => [
            'label' => 'Barang BMN',
            'plural_label' => 'Barang BMN',
            'navigation_label' => 'Rekap Inventaris BMN',
        ],
        'bmn_returns' => [
            'label' => 'Pengembalian BMN',
            'plural_label' => 'Pengembalian BMN',
            'navigation_label' => 'Pengembalian BMN',
        ],
        'bmn_submissions' => [
            'label' => 'Pengajuan BMN',
            'plural_label' => 'Pengajuan BMN',
            'navigation_label' => 'Pengajuan BMN Baru',
        ],
        'bookings' => [
            'label' => 'Booking Lab',
            'plural_label' => 'Booking Lab & Simulator',
            'navigation_label' => 'Booking Lab & Simulator',
        ],
        'books' => [
            'label' => 'Buku Perpustakaan',
            'plural_label' => 'Katalog Buku Perpustakaan',
            'navigation_label' => 'Katalog Buku Perpustakaan',
        ],
        'circulations' => [
            'label' => 'Sirkulasi Buku',
            'plural_label' => 'Sirkulasi & Denda',
            'navigation_label' => 'Sirkulasi & Denda',
        ],
        'competences' => [
            'label' => 'Kompetensi IMO / STCW',
            'plural_label' => 'Kompetensi IMO / STCW',
            'navigation_label' => 'Kompetensi IMO / STCW',
        ],
        'document_templates' => [
            'label' => 'Template Dokumen Cetak',
            'plural_label' => 'Template Dokumen Cetak',
            'navigation_label' => 'Template Dokumen Cetak',
        ],
        'employees' => [
            'label' => 'Pegawai & Dosen',
            'plural_label' => 'Data Pegawai & Dosen',
            'navigation_label' => 'Data Pegawai & Dosen',
        ],
        'imo_model_courses' => [
            'label' => 'IMO Model Course',
            'plural_label' => 'IMO Model Courses',
            'navigation_label' => 'IMO Model Courses',
        ],
        'materials' => [
            'label' => 'Bahan & Alat Praktik',
            'plural_label' => 'Bahan & Alat Praktik',
            'navigation_label' => 'Bahan & Alat Praktik',
        ],
        'official_residences' => [
            'label' => 'Rumah Dinas',
            'plural_label' => 'Rumah Dinas',
            'navigation_label' => 'Rumah Dinas',
        ],
        'residence_permits' => [
            'label' => 'Surat Izin Rumah Dinas (SIP)',
            'plural_label' => 'Surat Izin Rumah Dinas (SIP)',
            'navigation_label' => 'Surat Izin Rumah Dinas (SIP)',
        ],
        'rooms' => [
            'label' => 'Laboratorium & Ruangan',
            'plural_label' => 'Laboratorium & Ruangan',
            'navigation_label' => 'Laboratorium & Ruangan',
        ],
        'settings' => [
            'label' => 'Pengaturan Sistem',
            'plural_label' => 'Pengaturan Sistem',
            'navigation_label' => 'Pengaturan Sistem',
        ],
        'students' => [
            'label' => 'Taruna',
            'plural_label' => 'Data Taruna',
            'navigation_label' => 'Data Taruna',
        ],
        'subjects' => [
            'label' => 'Mata Kuliah Diklat',
            'plural_label' => 'Mata Kuliah Diklat',
            'navigation_label' => 'Mata Kuliah',
        ],
        'roles' => [
            'label' => 'Peran Pengguna',
            'plural_label' => 'Peran & Hak Akses',
            'navigation_label' => 'Peran & Hak Akses',
        ],
        'permissions' => [
            'label' => 'Izin Akses',
            'plural_label' => 'Kamus Izin Sistem',
            'navigation_label' => 'Kamus Izin Akses',
        ],
        'units' => [
            'label' => 'Unit Kerja & Prodi',
            'plural_label' => 'Unit Kerja & Prodi',
            'navigation_label' => 'Unit Kerja & Prodi',
        ],

        // Student panel specific
        'student_bookings' => [
            'label' => 'Booking Mandiri Lab',
            'plural_label' => 'Booking Mandiri Lab',
            'navigation_label' => 'Booking Mandiri Lab',
        ],
        'student_books' => [
            'label' => 'Katalog Buku',
            'plural_label' => 'Katalog Buku Perpustakaan',
            'navigation_label' => 'Katalog Perpustakaan',
        ],
        'student_circulations' => [
            'label' => 'Pinjaman Buku',
            'plural_label' => 'Pinjaman Buku Saya',
            'navigation_label' => 'Pinjaman Buku Saya',
        ],
    ],

    'widgets' => [
        'admin' => [
            'pending_bookings' => 'Booking Lab Menunggu Verifikasi',
            'approved_bookings_desc' => ':count sesi telah disetujui',
            'pending_bmn' => 'Pengajuan BMN Baru',
            'total_bmn_desc' => 'Total :count unit BMN terdaftar',
            'active_loans' => 'Buku Sedang Dipinjam',
            'active_loans_desc' => 'Transaksi sirkulasi aktif perpustakaan',
            'pending_sip' => 'Permohonan SIP Rumah Dinas',
            'pending_sip_desc' => 'Menunggu pemeriksaan / persetujuan',
        ],
        'student' => [
            'my_bookings' => 'Pengajuan Booking Mandiri Saya',
            'my_bookings_desc' => 'Riwayat sesi latihan lab / simulator',
            'active_loans' => 'Buku Sedang Dipinjam',
            'active_loans_desc' => 'Maksimal kuota 3 buku',
            'unpaid_fines' => 'Tunggakan Denda',
            'fines_desc_active' => 'Harap lunasi di loket perpustakaan',
            'fines_desc_none' => 'Tidak ada tunggakan',
        ],
        'filters' => [
            'this_month' => 'Bulan Ini',
            '3_months' => '3 Bulan Terakhir',
            'this_year' => 'Tahun Ini',
        ],
        'titles' => [
            'lab_utilization_trend' => 'Tren Utilisasi Lab & Simulator',
            'lab_usage_by_category' => 'Distribusi Pemakaian Lab per Kategori',
            'top_subjects_lab_usage' => 'Top Mata Kuliah Pengguna Lab',
            'imo_competence_coverage' => 'Cakupan Standar IMO Model Course',
            'today_lab_schedule' => 'Jadwal Sesi Lab & Simulator Hari Ini',
            'pending_lab_bookings' => 'Antrean Verifikasi Booking Lab',
            'low_stock_materials' => 'Peringatan Bahan Praktik Menipis',
            'bmn_condition_distribution' => 'Komposisi Kondisi Aset BMN',
            'bmn_per_unit' => 'Distribusi Aset BMN per Unit Kerja',
            'pending_bmn_submissions' => 'Antrean Pengajuan BMN Baru',
            'damaged_bmn_items' => 'Daftar Aset BMN Rusak Perlu Tindak Lanjut',
            'bmn_pending_decree_documents' => 'Aset BMN Siap Cetak Dokumen Penetapan',
            'residence_occupancy' => 'Status Okupansi Rumah Dinas',
            'residence_permit_status' => 'Statistik Permohonan SIP Rumah Dinas',
            'pending_residence_permits' => 'Antrean Permohonan SIP Rumah Dinas',
            'expiring_residence_permits' => 'Izin Rumah Dinas Mendekati Berakhir',
            'library_circulation_trend' => 'Tren Peminjaman & Pengembalian Buku',
            'top_borrowed_books' => 'Top 5 Buku Terpopuler Dipinjam',
            'books_by_category' => 'Koleksi Buku per Kategori Keilmuan',
            'overdue_circulations' => 'Peminjaman Melewati Jatuh Tempo & Denda',
            'today_library_circulations' => 'Aktivitas Sirkulasi Loket Hari Ini',
            'low_stock_books' => 'Buku Ketersediaan Kritis / Habis',
            'student_monthly_study_activity' => 'Aktivitas Praktik Mandiri & Pustaka',
            'student_upcoming_lab_sessions' => 'Jadwal Praktikum Lab Terdaftar',
            'student_active_loans' => 'Buku Sedang Dipinjam & Jatuh Tempo',
        ],
    ],

    'language_switcher' => [
        'switch_language' => 'Ganti Bahasa',
        'current_language' => 'Bahasa Aktif',
        'id' => 'Bahasa Indonesia',
        'en' => 'English (US)',
    ],
];
