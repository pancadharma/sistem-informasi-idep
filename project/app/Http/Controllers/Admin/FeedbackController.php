<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// Import facade lain jika diperlukan
use Illuminate\Support\Facades\Log; // Contoh jika perlu logging

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
        return view('tr.feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi untuk store (sudah benar)
        $validatedData = $request->validate([
            'program_id' => 'required|integer|exists:trprogram,id', // Validasi ID program
            // 'program' => 'nullable|string|max:255', // Validasi nama string (tidak dipakai)
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
            'status_complaint' => 'nullable|in:Baru,Diproses,Selesai,Ditolak',
        ]);

        // Set status default jika tidak dikirim
        if (!isset($validatedData['status_complaint'])) {
            $validatedData['status_complaint'] = 'Baru';
        }

        try {
            Feedback::create($validatedData);
            return redirect()->route('feedback.index')->with('success', 'Data feedback berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error storing feedback: ' . $e->getMessage()); // Tambahkan log error
            return redirect()->back()
                        ->withInput()
                        ->with('error', 'Terjadi kesalahan saat menyimpan data.'); // Pesan error generik
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('program'); // Load relasi program
        return view('tr.feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback)
    {
        $feedback->load('program'); // Load relasi program
        return view('tr.feedback.edit', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        // ===========================================
        // PERBAIKI VALIDASI DI SINI
        // ===========================================
        $validatedData = $request->validate([
            // Tambahkan validasi untuk program_id
            'program_id' => 'required|integer|exists:trprogram,id',
            // Hapus atau komentari validasi untuk 'program' (string)
            // 'program' => 'nullable|string|max:255',
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
            'status_complaint' => 'required|in:Baru,Diproses,Selesai,Ditolak', // Status wajib saat update
        ]);
        // ===========================================
        // AKHIR PERBAIKAN VALIDASI
        // ===========================================

        try {
            // Proses update sekarang akan menyertakan program_id dari $validatedData
            $feedback->update($validatedData);
            return redirect()->route('feedback.index')->with('success', 'Data feedback berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating feedback ID ' . $feedback->id . ': ' . $e->getMessage()); // Log error
            return redirect()->back()
                         ->withInput()
                         ->with('error', 'Terjadi kesalahan saat memperbarui data.'); // Pesan error generik
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
