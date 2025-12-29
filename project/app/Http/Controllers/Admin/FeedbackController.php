<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use App\Models\User; // PENTING: Import model User Anda
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// Import facade lain jika diperlukan
use Illuminate\Support\Facades\Log; // Contoh jika perlu logging
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Feedback::query()->with('program'); // Eager load relasi

        // Filter berdasarkan nama program via relasi
        if ($request->has('program') && $request->program != '') {
            $searchTerm = $request->program;
            $query->whereHas('program', function ($q) use ($searchTerm) {
                // Ganti 'nama' jika nama kolom di tabel 'trprogram' Anda berbeda
                $q->where('nama', 'like', '%' . $searchTerm . '%');
            });
        }

        $feedbackItems = $query->latest()->paginate(10);
        return view('tr.feedback.index', compact('feedbackItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         // Ambil user beserta relasi jabatannya
    // Kita butuh ID user untuk value, nama user untuk display, dan nama jabatan untuk auto-fill
    $handlers = User::with('jabatan') // Pastikan 'jabatan' adalah nama relasi yang benar di model User
                    ->orderBy('nama')   // Asumsi 'nama' adalah kolom nama di tabel users
                    ->get(['id', 'nama', 'jabatan_id']); // Ambil jabatan_id juga untuk debug


    // --- MULAI BLOK DEBUGGING ---
    // Log::info('--- Data Handler untuk Dropdown Feedback Create ---');
    // foreach ($handlers as $userLoopItem) {
    //     $posisiOutput = 'N/A atau Relasi Jabatan Gagal'; // Default message
    //     if ($userLoopItem->jabatan) { // Cek apakah objek jabatan ada (relasi berhasil)
    //         // Akses kolom 'nama' dari objek jabatan
    //         $posisiOutput = $userLoopItem->jabatan->nama ?? 'Kolom "nama" di tabel jabatan KOSONG/NULL'; 
    //     } elseif ($userLoopItem->jabatan_id) {
    //         $posisiOutput = 'jabatan_id (' . $userLoopItem->jabatan_id . ') ada, tapi objek jabatan null (cek model MJabatan/data di tabel mjabatan).';
    //     } else {
    //         $posisiOutput = 'User tidak memiliki jabatan_id.';
    //     }
    //     Log::info("User: {$userLoopItem->nama} (ID: {$userLoopItem->id}), Jabatan_ID: {$userLoopItem->jabatan_id}, Nilai untuk data-position akan menjadi: '{$posisiOutput}'");
    // }
    // Log::info('-------------------------------------------------');
    // --- AKHIR BLOK DEBUGGING ---

        return view('tr.feedback.create', compact('handlers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
     {
        $validatedData = $request->validate([
             'program_id' => 'required|integer|exists:trprogram,id', // Pastikan 'trprogram' adalah nama tabel program Anda
            'nama_pelapor' => 'nullable|string|max:255',
            'tanggal_registrasi' => 'required|date',
            'umur' => 'nullable|integer|min:0',
            'penerima' => 'nullable|string|max:255',
            'sort_of_complaint' => 'required|string|max:255',
            'age_group' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:255',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_registrasi',
            
            // SESUAIKAN DENGAN create.blade.php Anda:
            'sex' => ['nullable', Rule::in(['', 'Male', 'Female', 'Boy', 'Girl', 'Unspecified'])],
            
            'kontak_penerima' => 'nullable|string|max:255',

             // MENGGANTI VALIDASI 'handler' MENJADI 'handler_id'
        'handler_id' => 'nullable|integer|exists:users,id', // Handler sekarang adalah ID user
        'position_handler' => 'nullable|string|max:255', // Tetap string, akan diisi otomatis


            'phone_number' => 'nullable|string|max:20',

            // SESUAIKAN DENGAN create.blade.php Anda:
            'channels' => ['nullable', Rule::in([
                '', 'Complaint Form', 'Complaint Box', 'Face to Face', 'Hotline', 
                'Help Desk', 'SMS', 'WhatsApp', 'Children Consultation', 
                'Local Agency', 'Others'
            ])],

            'position_handler' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'other_channel' => 'nullable|string|max:255',
            'kontak_handler' => 'nullable|string|max:255',
            'kategori_komplain' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',

            // SESUAIKAN DENGAN create.blade.php Anda ('Process', 'Resolved') dan logika default:
            'status_complaint' => ['nullable', Rule::in(['', 'Process', 'Resolved'])],
            
            // ============================================================
            // PERBAIKAN ATURAN VALIDASI UNTUK FIELD BARU:
            // ============================================================
            'field_office' => [
                'nullable',
                'string',
                Rule::in([ // Opsi dari create.blade.php
                    '', 'Bali', 'Bangka Belitung', 'Jawa Timur', 'Kalimantan Timur', 
                    'Kalimantan Utara', 'NTT', 'Papua Barat', 'Sulawesi Tengah', 'Yogyakarta'
                ]),
            ],
            'is_hidden' => 'nullable|boolean', // Untuk select dengan value 0/1
            'handler_description' => 'nullable|string',
            // ============================================================

            // Tambahkan validasi untuk field display program jika ingin `old()` bekerja untuknya
            // Ini opsional, karena field ini readonly dan diisi JS.
            // 'kode_program_display' => 'nullable|string',
            // 'nama_program_display' => 'nullable|string',
        ]);

        $dataToCreate = $validatedData;
        
        // Menangani nilai default untuk 'is_hidden' jika Anda menggunakan SELECT di form
        // Jika 'is_hidden' dikirim sebagai string "0" atau "1" dari select,
        // Laravel akan mengkonversinya ke boolean jika ada cast di model atau tipe kolomnya boolean.
        // Jika Anda menggunakan checkbox, logika $request->has('is_hidden') lebih cocok.
        // Karena di create.blade.php Anda menggunakan SELECT untuk is_hidden:
        // $dataToCreate['is_hidden'] akan berisi nilai dari select (0 atau 1) jika dipilih, atau null jika '--Pilih--'
        // Jika null, dan kolom DB adalah boolean dengan default false, itu akan jadi false.
        // Jika Anda ingin memastikan boolean secara eksplisit:
        if (isset($dataToCreate['is_hidden'])) {
            $dataToCreate['is_hidden'] = filter_var($dataToCreate['is_hidden'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $dataToCreate['is_hidden'] = false; // Default jika tidak ada input sama sekali
        }


        // Logika untuk default 'status_complaint' jika kosong
        if (empty($dataToCreate['status_complaint'])) {
            $dataToCreate['status_complaint'] = 'Baru'; // Pastikan 'Baru' adalah status yang valid untuk disimpan
        }

        try {
            Feedback::create($dataToCreate);
            return redirect()->route('feedback.index')->with('success', 'Data feedback berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error storing feedback: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()); // Tampilkan pesan error (hati-hati di produksi)
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('program', 'handlerUser'); // Load relasi program
        return view('tr.feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback)
    {
        $feedback->load('program', 'handlerUser.jabatan'); // Load relasi program

         // Ambil daftar semua user untuk dropdown handler
       $handlers = User::with('jabatan') // Pastikan 'jabatan' adalah nama relasi yang benar di model User
                    ->orderBy('nama')   // Asumsi 'nama' adalah kolom nama di tabel users
                    ->get(['id', 'nama', 'jabatan_id']); // Ambil jabatan_id juga untuk debug
        return view('tr.feedback.edit', compact('feedback', 'handlers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
{
        $validatedData = $request->validate([
            'program_id' => 'required|integer|exists:trprogram,id',
            'kode_program_display' => 'nullable|string', // Untuk old() helper
            'nama_program_display' => 'nullable|string', // Untuk old() helper
            'nama_pelapor' => 'nullable|string|max:255',
            'tanggal_registrasi' => 'required|date',
            'umur' => 'nullable|integer|min:0',
            'penerima' => 'nullable|string|max:255',
            'sort_of_complaint' => 'required|string|max:255',
            'age_group' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:255',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_registrasi',
            'sex' => ['nullable', Rule::in(['', 'Male', 'Female', 'Boy', 'Girl', 'Unspecified'])],
            'kontak_penerima' => 'nullable|string|max:255',
            // Validasi untuk handler_id (ID User) dan position_handler (string nama posisi)
            'handler_id' => 'nullable|integer|exists:users,id', // ID User sebagai handler
            'position_handler' => 'nullable|string|max:255', // Nama posisi handler (diisi JS)

            'phone_number' => 'nullable|string|max:20',
            'channels' => ['nullable', Rule::in([
                '', 'Complaint Form', 'Complaint Box', 'Face to Face', 'Hotline', 
                'Help Desk', 'SMS', 'WhatsApp', 'Children Consultation', 
                'Local Agency', 'Others'
            ])],
            'address' => 'nullable|string',
            'other_channel' => 'nullable|string|max:255',
            'kontak_handler' => 'nullable|string|max:255',
            'kategori_komplain' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            // Saat update, status mungkin bisa lebih banyak pilihan dan wajib diisi
            'status_complaint' => ['nullable', Rule::in(['', 'Process', 'Resolved'])], 
            
            'field_office' => [
                'nullable',
                'string',
                Rule::in([
                    '', 'Bali', 'Bangka Belitung', 'Jawa Timur', 'Kalimantan Timur', 
                    'Kalimantan Utara', 'NTT', 'Papua Barat', 'Sulawesi Tengah', 'Yogyakarta'
                ]),
            ],
            'is_hidden' => 'required|boolean', // Saat update, mungkin lebih baik 'required'
            'handler_description' => 'nullable|string',
        ]);

        $dataToUpdate = $validatedData;

         // Hapus field display program sebelum update
        unset($dataToUpdate['kode_program_display']);
        unset($dataToUpdate['nama_program_display']);

        // Penanganan is_hidden jika dari form select (0 atau 1)
        if (isset($dataToUpdate['is_hidden'])) {
            $dataToUpdate['is_hidden'] = filter_var($dataToUpdate['is_hidden'], FILTER_VALIDATE_BOOLEAN);
        }


        try {
            $feedback->update($dataToUpdate);
            return redirect()->route('feedback.index')->with('success', 'Data feedback berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating feedback ID ' . $feedback->id . ': ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        try {
            $feedback->delete();
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
            }
            return redirect()->route('feedback.index')->with('success', 'Data feedback berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting feedback ID ' . $feedback->id . ': ' . $e->getMessage()); // Log error
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500); // Pesan generik
            }
            return redirect()->route('feedback.index')->with('error', 'Gagal menghapus data.'); // Pesan generik
        }
    }
}
