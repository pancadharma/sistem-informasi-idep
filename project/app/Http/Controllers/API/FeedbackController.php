<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback; // Pastikan ini di-import
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\DB; // Import DB facade

class FeedbackController extends Controller
{
    /**
     * Menyediakan data untuk DataTables.
     */
    public function datatable(Request $request)
    {
        // ===========================================
        // UBAH QUERY: Pilih kolom secara eksplisit
        // ===========================================
        $query = Feedback::query()
            ->with('program') // Tetap eager load relasi
            ->select([
                'feedback.*', // Pilih semua kolom dari tabel feedback
                // Pastikan ID feedback dipilih secara eksplisit
                // Jika nama tabel Anda bukan 'feedback', sesuaikan di sini
                DB::raw('feedback.id as feedback_id_explicit') // Ambil feedback.id dan beri alias
            ]);
        // ===========================================


        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('tanggal_registrasi', function ($row) {
                return $row->tanggal_registrasi ? $row->tanggal_registrasi->format('d M Y') : '-';
            })
            ->addColumn('program_nama', function ($row) {
                // Ganti 'nama' jika nama kolom di tabel 'trprogram' Anda berbeda
                return $row->program?->nama ?? 'N/A';
            })
            ->addColumn('status_badge', function ($row) {
                 $status = $row->status_complaint ?? 'N/A';
                 $class = 'bg-secondary';

                if ($status == 'Process') {
                    $class = 'bg-secondary'; // Abu-abu untuk Process
                } elseif ($status == 'Resolved') {
                    $class = 'bg-success';   // Hijau untuk Resolved
                } 
                //  if ($status == 'Baru') $class = 'bg-info';
                //  elseif ($status == 'Diproses') $class = 'bg-warning text-dark';
                //  elseif ($status == 'Selesai') $class = 'bg-success';
                //  elseif ($status == 'Ditolak') $class = 'bg-danger';
                 return '<span class="badge ' . htmlspecialchars($class) . '">' . htmlspecialchars($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                // ===========================================
                // GUNAKAN ID EKSPLISIT YANG SUDAH DI-ALIAS
                // ===========================================
                // Ambil ID feedback dari kolom yang sudah kita pilih secara eksplisit
                $feedbackId = $row->feedback_id_explicit; // <-- Gunakan alias dari select()
                // ===========================================

                // Log ID yang digunakan (seharusnya sekarang benar)
                Log::info('Generating action buttons for Feedback ID (explicit): ' . $feedbackId);
                // Log::info('Row data for Feedback ID ' . $feedbackId . ':', $row->toArray()); // Log row jika masih perlu

                $buttons = [];

                try {
                    // Gunakan $feedbackId yang sudah benar
                    $showUrl = route('feedback.show', ['feedback' => $feedbackId]);
                    $editUrl = route('feedback.edit', ['feedback' => $feedbackId]);
                    $deleteRoute = route('feedback.destroy', ['feedback' => $feedbackId]);

                    Log::info('Generated Show URL for Feedback ID ' . $feedbackId . ': ' . $showUrl);

                    $buttons[] = '<a href="' . e($showUrl) . '" class="btn btn-info btn-sm" title="' . e(__('Lihat Detail')) . '"><i class="fas fa-eye"></i></a>';
                    $buttons[] = '<a href="' . e($editUrl) . '" class="btn btn-warning btn-sm" title="' . e(__('Edit')) . '"><i class="fas fa-edit"></i></a>';
                    $buttons[] = '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.e($feedbackId).'" data-route="'.e($deleteRoute).'" title="' . e(__('Hapus')) . '"><i class="fas fa-trash"></i></button>';

                } catch (\Exception $e) {
                    Log::error('Error generating route for Feedback ID ' . $feedbackId . ': ' . $e->getMessage());
                    return 'Error generating actions';
                }

                return implode(' ', $buttons);
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    // ... method lain ...
}
