<?php

namespace Database\Seeders;

use App\Models\Core\DocumentTemplate;
use Illuminate\Database\Seeder;

class DocumentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'type' => 'booking_confirmation',
                'name' => 'Surat Konfirmasi Penggunaan Laboratorium / Simulator',
                'body' => '<div style="font-family: sans-serif; padding: 24px;">
                    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px;">
                        <h2 style="margin: 0;">SEKOLAH TINGGI ILMU PELAYARAN JAKARTA</h2>
                        <h4 style="margin: 4px 0;">UNIT SARANA PRAKTIK PELAUT (SPP)</h4>
                        <p style="margin: 0; font-size: 12px;">Jl. Marunda Makmur No. 1, Cilincing, Jakarta Utara</p>
                    </div>
                    <h3 style="text-align: center; text-decoration: underline;">BUKTI KONFIRMASI BOOKING LABORATORIUM</h3>
                    <p style="text-align: center; margin-top: -10px;">Nomor: {{ document_number }}</p>
                    <table style="width: 100%; margin-top: 20px; font-size: 14px; line-height: 1.6;">
                        <tr><td style="width: 25%;">Kode Booking</td><td>: {{ booking_number }}</td></tr>
                        <tr><td>Laboratorium</td><td>: {{ room_name }} ({{ room_code }})</td></tr>
                        <tr><td>Mata Kuliah</td><td>: {{ subject_name }}</td></tr>
                        <tr><td>Dosen Penanggung Jawab</td><td>: {{ responsible_lecturer }}</td></tr>
                        <tr><td>Pemohon</td><td>: {{ requester_name }}</td></tr>
                        <tr><td>Jadwal Praktik</td><td>: {{ start_at }} s.d. {{ end_at }}</td></tr>
                        <tr><td>Jumlah Peserta</td><td>: {{ participant_count }} Orang</td></tr>
                    </table>
                    <div style="margin-top: 50px; float: right; text-align: center; width: 220px;">
                        <p>Jakarta, {{ date }}<br>Kepala Unit SPP</p>
                        <br><br><br>
                        <p><strong>{{ approver_name }}</strong><br>NIP. {{ approver_nip }}</p>
                    </div>
                </div>',
                'is_active' => true,
            ],
            [
                'type' => 'bmn_decree',
                'name' => 'Surat Penetapan Penggunaan Barang Milik Negara',
                'body' => '<div style="font-family: sans-serif; padding: 24px;">
                    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px;">
                        <h2 style="margin: 0;">SEKOLAH TINGGI ILMU PELAYARAN JAKARTA</h2>
                        <h4 style="margin: 4px 0;">PENGELOLA BARANG MILIK NEGARA (BMN)</h4>
                    </div>
                    <h3 style="text-align: center; text-decoration: underline;">SURAT PENETAPAN STATUS PENGGUNAAN BMN</h3>
                    <p style="text-align: center; margin-top: -10px;">Nomor: {{ document_number }}</p>
                    <p>Menerangkan bahwa Barang Milik Negara di bawah ini telah diverifikasi dan ditetapkan penempatannya:</p>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;" border="1">
                        <thead>
                            <tr style="background: #f0f0f0;">
                                <th style="padding: 6px;">Kode BMN / NUP</th>
                                <th style="padding: 6px;">Nama Barang</th>
                                <th style="padding: 6px;">Merk / Tipe</th>
                                <th style="padding: 6px;">Unit Pengguna</th>
                                <th style="padding: 6px;">Ruangan</th>
                                <th style="padding: 6px;">Penanggung Jawab</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 6px;">{{ bmn_code }} / {{ register_number }}</td>
                                <td style="padding: 6px;">{{ item_name }}</td>
                                <td style="padding: 6px;">{{ brand }} {{ model }}</td>
                                <td style="padding: 6px;">{{ unit_name }}</td>
                                <td style="padding: 6px;">{{ room_name }}</td>
                                <td style="padding: 6px;">{{ responsible_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top: 50px; float: right; text-align: center; width: 220px;">
                        <p>Jakarta, {{ date }}<br>Petugas Pengelola BMN</p>
                        <br><br><br>
                        <p><strong>{{ verified_by_name }}</strong><br>NIP. {{ verified_by_nip }}</p>
                    </div>
                </div>',
                'is_active' => true,
            ],
            [
                'type' => 'bmn_return_receipt',
                'name' => 'Tanda Terima Pengembalian Barang Milik Negara',
                'body' => '<div style="font-family: sans-serif; padding: 24px;">
                    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px;">
                        <h2 style="margin: 0;">SEKOLAH TINGGI ILMU PELAYARAN JAKARTA</h2>
                        <h4 style="margin: 4px 0;">PENGELOLA BARANG MILIK NEGARA (BMN)</h4>
                    </div>
                    <h3 style="text-align: center; text-decoration: underline;">BUKTI PENGEMBALIAN BMN</h3>
                    <p style="text-align: center; margin-top: -10px;">Nomor: {{ document_number }}</p>
                    <table style="width: 100%; margin-top: 20px; font-size: 14px; line-height: 1.6;">
                        <tr><td style="width: 25%;">Nama Barang</td><td>: {{ item_name }}</td></tr>
                        <tr><td>Kode BMN</td><td>: {{ bmn_code }}</td></tr>
                        <tr><td>Pengembali</td><td>: {{ requester_name }}</td></tr>
                        <tr><td>Tanggal Pengembalian</td><td>: {{ return_date }}</td></tr>
                        <tr><td>Kondisi Saat Kembali</td><td>: {{ condition_label }}</td></tr>
                        <tr><td>Keterangan / Kerusakan</td><td>: {{ damage_note }}</td></tr>
                    </table>
                </div>',
                'is_active' => true,
            ],
            [
                'type' => 'residence_permit',
                'name' => 'Surat Izin Penghuni (SIP) Rumah Dinas',
                'body' => '<div style="font-family: sans-serif; padding: 24px;">
                    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px;">
                        <h2 style="margin: 0;">SEKOLAH TINGGI ILMU PELAYARAN JAKARTA</h2>
                        <p style="margin: 0; font-size: 12px;">Jl. Marunda Makmur No. 1, Cilincing, Jakarta Utara 14150</p>
                    </div>
                    <h3 style="text-align: center; text-decoration: underline;">SURAT IZIN PENGHUNI (SIP) RUMAH DINAS</h3>
                    <p style="text-align: center; margin-top: -10px;">Nomor: {{ document_number }}</p>
                    <p>Ketua Sekolah Tinggi Ilmu Pelayaran Jakarta memberikan izin penghunian rumah dinas kepada:</p>
                    <table style="width: 100%; margin-top: 20px; font-size: 14px; line-height: 1.6;">
                        <tr><td style="width: 25%;">Nama Pegawai</td><td>: {{ employee_name }}</td></tr>
                        <tr><td>NIP / NIK</td><td>: {{ employee_number }}</td></tr>
                        <tr><td>Unit Kerja</td><td>: {{ unit_name }}</td></tr>
                        <tr><td>Nomor Rumah Dinas</td><td>: {{ house_number }}</td></tr>
                        <tr><td>Alamat</td><td>: {{ house_address }}</td></tr>
                        <tr><td>Masa Berlaku Izin</td><td>: {{ occupancy_start }} s.d. {{ occupancy_end }}</td></tr>
                    </table>
                    <div style="margin-top: 50px; float: right; text-align: center; width: 220px;">
                        <p>Jakarta, {{ date }}<br>Ketua STIP Jakarta</p>
                        <br><br><br>
                        <p><strong>{{ leader_name }}</strong><br>NIP. {{ leader_nip }}</p>
                    </div>
                </div>',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $data) {
            DocumentTemplate::updateOrCreate(
                ['type' => $data['type']],
                $data
            );
        }
    }
}
