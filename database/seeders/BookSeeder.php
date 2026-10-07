<?php

namespace Database\Seeders;

use App\Models\Library\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'book_code' => 'BK-NAU-001',
                'isbn' => '978-602-01-0001-1',
                'title' => 'Ilmu Pelayaran Datar dan Navigasi Darat',
                'author' => 'Capt. Arso Martopo',
                'publisher' => 'Penerbit Maritim Mandiri',
                'year' => 2020,
                'category' => 'Nautika',
                'shelf' => 'Rak A-1',
                'total_stock' => 10,
                'available_stock' => 10,
            ],
            [
                'book_code' => 'BK-NAU-002',
                'isbn' => '978-602-01-0002-8',
                'title' => 'Stabilitas Kapal dan Penanganan Muatan Modern',
                'author' => 'Capt. D. A. Derrett & Dr. C. B. Barrass',
                'publisher' => 'Elsevier Maritime Press',
                'year' => 2018,
                'category' => 'Nautika',
                'shelf' => 'Rak A-2',
                'total_stock' => 8,
                'available_stock' => 8,
            ],
            [
                'book_code' => 'BK-TEK-001',
                'isbn' => '978-602-02-0001-5',
                'title' => 'Motor Diesel Penggerak Utama Kapal',
                'author' => 'Ir. Soekirno, M.T.',
                'publisher' => 'Penerbit Teknik Bahari',
                'year' => 2021,
                'category' => 'Teknika',
                'shelf' => 'Rak B-1',
                'total_stock' => 12,
                'available_stock' => 12,
            ],
            [
                'book_code' => 'BK-TEK-002',
                'isbn' => '978-602-02-0002-2',
                'title' => 'Sistem Kelistrikan dan Otomasi di Atas Kapal',
                'author' => 'Dr. Ing. Hadi Pranoto',
                'publisher' => 'Graha Kelautan',
                'year' => 2022,
                'category' => 'Teknika',
                'shelf' => 'Rak B-2',
                'total_stock' => 15,
                'available_stock' => 15,
            ],
            [
                'book_code' => 'BK-KLK-001',
                'isbn' => '978-602-03-0001-9',
                'title' => 'Manajemen Pelabuhan dan Logistik Maritim Global',
                'author' => 'Prof. Dr. Herman Susanto',
                'publisher' => 'Pustaka Maritim Indonesia',
                'year' => 2023,
                'category' => 'KALK',
                'shelf' => 'Rak C-1',
                'total_stock' => 10,
                'available_stock' => 10,
            ],
            [
                'book_code' => 'BK-UMM-001',
                'isbn' => '978-602-04-0001-3',
                'title' => 'Standard Marine Communication Phrases (IMO SMCP)',
                'author' => 'International Maritime Organization (IMO)',
                'publisher' => 'IMO Publishing',
                'year' => 2019,
                'category' => 'Umum',
                'shelf' => 'Rak D-1',
                'total_stock' => 20,
                'available_stock' => 20,
            ],
            [
                'book_code' => 'BK-UMM-002',
                'isbn' => '978-602-04-0002-0',
                'title' => 'Konvensi Internasional STCW 1978 Amandemen Manila 2010',
                'author' => 'Sekretariat Jenderal IMO',
                'publisher' => 'Kementerian Perhubungan RI',
                'year' => 2017,
                'category' => 'Hukum Maritim',
                'shelf' => 'Rak D-2',
                'total_stock' => 14,
                'available_stock' => 14,
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(
                ['book_code' => $book['book_code']],
                $book
            );
        }
    }
}
