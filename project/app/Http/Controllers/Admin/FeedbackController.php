<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use Illuminate\Http\Request; // Import Request
use Illuminate\Support\Facades\Validator; // Import Validator Facade (opsional untuk validasi custom)
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Tambahkan Request $request
    {
        // Ambil query builder
        $query = Feedback::query();

        // Terapkan filter jika ada parameter 'program'
        if ($request->has('program') && $request->program != '') {
            $query->where('program', 'like', '%' . $request->program . '%');
        }

        // Urutkan berdasarkan data terbaru, lalu paginasi
        $feedbackItems = $query->latest()->paginate(10); // Tampilkan 10 item per halaman

        // Kirim data ke view index
        return view('tr.feedback.index', compact('feedbackItems'));
    }

    /**
     * Show the form for creating a new resource.
     * (Tidak digunakan secara langsung karena kita pakai modal di index)
     */
    public function create()
    {
        // Biasanya hanya return view('feedback.create');
        // Tapi karena pakai modal, logika tambah ada di index/store
         return redirect()->route('feedback.index')->with('info', 'Gunakan tombol "Tambah FRM" untuk menambahkan data baru.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'program' => 'nullable|string|max:255',
            'tanggal_registrasi' => 'required|date',
            'umur' => 'nullable|integer|min:0',
            'penerima' => 'nullable|string|max:255',
            'sort_of_complaint' => 'required|string|max:255',
            'age_group' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:255',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_registrasi',
            'sex' => 'nullable|in:Laki-laki,Perempuan,Lainnya',
            'kontak_penerima' => 'nullable|string|max:255',
            'handler' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'channels' => 'nullable|string|max:255',
            'position_handler' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'other_channel' => 'nullable|string|max:255',
            'kontak_handler' => 'nullable|string|max:255',
            'kategori_komplain' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'status_complaint' => 'nullable|in:Baru,Diproses,Selesai,Ditolak', // Sesuaikan jika perlu
        ]);

        // 2. Set status default jika tidak dikirim dari form
         if (!isset($validatedData['status_complaint'])) {
             $validatedData['status_complaint'] = 'Baru'; // Default status
         }

        // 3. Simpan ke Database
        try {
            Feedback::create($validatedData);

            // 4. Redirect ke halaman index dengan pesan sukses
            return redirect()->route('feedback.index')->with('success', 'Data feedback berhasil ditambahkan!');

        } catch (\Exception $e) {
             // Jika terjadi error saat menyimpan
             // Log error jika perlu: Log::error($e->getMessage());
             return redirect()->back()
                        ->withInput() // Kembalikan input sebelumnya
                        ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()); // Tampilkan pesan error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback) // Gunakan Route Model Binding
    {
        // Data $feedback otomatis diambil berdasarkan ID di URL
        return view('tr.feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback) // Gunakan Route Model Binding
    {
         // Data $feedback otomatis diambil berdasarkan ID di URL
        return view('tr.feedback.edit', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback) // Gunakan Route Model Binding
    {
        // 1. Validasi Input (mirip dengan store, sesuaikan jika perlu)
        $validatedData = $request->validate([
             'program' => 'nullable|string|max:255',
             'tanggal_registrasi' => 'required|date',
             'umur' => 'nullable|integer|min:0',
             'penerima' => 'nullable|string|max:255',
             'sort_of_complaint' => 'required|string|max:255',
             'age_group' => 'nullable|string|max:100',
             'position' => 'nullable|string|max:255',
             'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_registrasi',
             'sex' => 'nullable|in:Laki-laki,Perempuan,Lainnya',
             'kontak_penerima' => 'nullable|string|max:255',
             'handler' => 'nullable|string|max:255',
             'phone_number' => 'nullable|string|max:20',
             'channels' => 'nullable|string|max:255',
             'position_handler' => 'nullable|string|max:255',
             'address' => 'nullable|string',
             'other_channel' => 'nullable|string|max:255',
             'kontak_handler' => 'nullable|string|max:255',
             'kategori_komplain' => 'nullable|string|max:255',
             'deskripsi' => 'required|string',
             'status_complaint' => 'required|in:Baru,Diproses,Selesai,Ditolak', // Status wajib diisi saat update
        ]);

        // 2. Update data di Database
        try {
             $feedback->update($validatedData);

             // 3. Redirect ke halaman index (atau halaman detail) dengan pesan sukses
             return redirect()->route('feedback.index')->with('success', 'Data feedback berhasil diperbarui!');

         } catch (\Exception $e) {
              // Jika terjadi error saat update
              return redirect()->back()
                         ->withInput()
                         ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback) // Gunakan Route Model Binding
    {
        try {
             $feedback->delete();
             return redirect()->route('feedback.index')->with('success', 'Data feedback berhasil dihapus!');
         } catch (\Exception $e) {
              // Jika terjadi error saat hapus (misal karena relasi/foreign key)
               return redirect()->route('feedback.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
         }
    }
}