<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// (Sangat Penting) Pastikan Anda meng-import model Program Anda di sini
// Ganti App\Models\Program jika path atau nama model Anda berbeda
use App\Models\Program;
// Jika model Anda namanya Trprogram: use App\Models\Trprogram;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedback'; // Nama tabel sudah benar

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program_id',           // Sudah Benar
        // 'program',           // Sudah Benar (dikomentari/dihapus)
        'nama_pelapor',
        'tanggal_registrasi',
        'umur',
        'penerima',
        'sort_of_complaint',
        'age_group',
        'position',
        'tanggal_selesai',
        'sex',
        'kontak_penerima',
        'handler',
        'phone_number',
        'channels',
        'position_handler',
        'address',
        'other_channel',
        'kontak_handler',
        'kategori_komplain',
        'deskripsi',
        'status_complaint',
    ];

     /**
      * The attributes that should be cast.
      *
      * @var array<string, string>
      */
     protected $casts = [
         'tanggal_registrasi' => 'date',
         'tanggal_selesai' => 'date',
         'umur' => 'integer', // Cast umur ke integer (sudah ada)
     ];

    // ===========================================
    // TAMBAHKAN METHOD RELASI INI
    // ===========================================
    /**
     * Mendapatkan data Program yang terkait dengan Feedback ini.
     * ('program' adalah nama method relasinya)
     */
    public function program() // Nama method relasi (konvensi: nama model lowercase)
    {
        // return $this->belongsTo(NamaModelProgram::class, 'foreign_key', 'owner_key');
        // - NamaModelProgram::class -> Class Model Program Anda (misal: Program::class)
        // - 'foreign_key' -> Nama kolom di tabel 'feedback' (misal: 'program_id')
        // - 'owner_key' -> Nama primary key di tabel 'trprogram' (biasanya 'id', opsional)

        // Ganti Program::class jika nama model Anda Trprogram::class atau lainnya
        return $this->belongsTo(Program::class, 'program_id');
    }
    // ===========================================

}