<?php

return [
    'request_status' => [
        'draft' => 'Draf',
        'submitted' => 'Menunggu Pemeriksaan',
        'revision_requested' => 'Perlu Revisi',
        'verified' => 'Terverifikasi',
        'approved' => 'Disetujui',
        'in_use' => 'Sedang Digunakan',
        'completed' => 'Selesai',
        'rejected' => 'Ditolak',
        'cancelled' => 'Dibatalkan',
        'no_show' => 'Tidak Hadir',
        'expired' => 'Kedaluwarsa',
    ],

    'circulation_status' => [
        'borrowed' => 'Dipinjam',
        'returned' => 'Dikembalikan',
    ],

    'item_condition' => [
        'good' => 'Baik',
        'minor_damage' => 'Rusak Ringan',
        'major_damage' => 'Rusak Berat',
        'lost' => 'Hilang',
    ],

    'bmn_item_status' => [
        'active' => 'Aktif',
        'returned' => 'Dikembalikan',
        'disposed' => 'Dihapuskan',
    ],

    'bmn_movement_source' => [
        'submission' => 'Pengajuan Baru',
        'return' => 'Pengembalian',
        'manual' => 'Manual / Mutasi',
    ],

    'material_type' => [
        'consumable' => 'Bahan Habis Pakai',
        'equipment' => 'Peralatan',
        'module' => 'Modul Praktik',
    ],

    'room_type' => [
        'lab' => 'Laboratorium / Simulator',
        'classroom' => 'Ruang Kelas',
        'office' => 'Ruang Kantor',
        'warehouse' => 'Gudang',
        'other' => 'Lainnya',
    ],

    'subject_category' => [
        'teknika' => 'Teknika',
        'nautika' => 'Nautika',
        'kalk' => 'KALK',
    ],

    'unit_type' => [
        'study_program' => 'Program Studi',
        'work_unit' => 'Unit Kerja',
        'service_unit' => 'Unit Layanan',
    ],
];
