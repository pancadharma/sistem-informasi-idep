<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MPendonor;
use App\Models\Kategori_Pendonor;

class PendonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendonors = [
            ['mpendonorkategori_id' => 1, 'nama' => 'Badan Pemeriksa Keuangan (BPK)', 'pic' => 'Andi Wijaya', 'email' => 'contact@bpk.go.id', 'phone' => '021-1234567', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Pertanian (Kementan)', 'pic' => 'Siti Nurhaliza', 'email' => 'info@kementan.go.id', 'phone' => '021-2345678', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Lingkungan Hidup dan Kehutanan (KLHK)', 'pic' => 'Rina Sari', 'email' => 'contact@klhk.go.id', 'phone' => '021-3456789', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Perdagangan (Kemendag)', 'pic' => 'Budi Santoso', 'email' => 'info@kemendag.go.id', 'phone' => '021-4567890', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Perumahan Rakyat dan Permukiman (Kemperum)', 'pic' => 'Dewi Kusuma', 'email' => 'contact@kemperum.go.id', 'phone' => '021-5678901', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Kesehatan (Kemenkes)', 'pic' => 'Rina Wijaya', 'email' => 'info@kemenkes.go.id', 'phone' => '021-6789012', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Pariwisata dan Ekonomi Kreatif (Kemenparekraf)', 'pic' => 'Larasati', 'email' => 'contact@kemenparekraf.go.id', 'phone' => '021-7890123', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Perindustrian (Kemenperin)', 'pic' => 'Agus Setiawan', 'email' => 'info@kemenperin.go.id', 'phone' => '021-8901234', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Pendidikan dan Kebudayaan (Kemendikbud)', 'pic' => 'Sri Mulyani', 'email' => 'contact@kemendikbud.go.id', 'phone' => '021-9012345', 'aktif' => 1],
            ['mpendonorkategori_id' => 1, 'nama' => 'Kementerian Perumahan Rakyat dan Permukiman (Kemperum)', 'pic' => 'Dewi Kusuma', 'email' => 'contact@kemperum.go.id', 'phone' => '021-7890123', 'aktif' => 1],
        ];

        foreach ($pendonors as $pendonor) {
            MPendonor::updateOrInsert(
                ['nama' => $pendonor['nama']], // condition to find existing record
                $pendonor // data to update or insert
            );
        }
    }
}
