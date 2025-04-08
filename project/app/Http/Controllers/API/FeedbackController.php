<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback; // Sesuaikan namespace jika berbeda
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate; // Jika perlu cek permission

class FeedbackController extends Controller
{
    /**
     * Menyediakan data untuk DataTables.
     */
    public function datatable(Request $request)
    {
        // Mulai query Eloquent
        $query = Feedback::query()->select('feedback.*'); // Pilih kolom dari tabel feedback

        // (Opsional) Handle filter kustom jika ada (misal dari tombol filter program Anda)
        if ($request->filled('program')) {
             $query->where('program', 'like', '%' . $request->input('program') . '%');
        }

        return DataTables::of($query) // Gunakan $query (bukan ->get())
            ->addIndexColumn() // Tambah kolom nomor urut DT_RowIndex
            ->editColumn('tanggal_registrasi', function ($row) {
                // Format tanggal langsung di sini
                return $row->tanggal_registrasi ? $row->tanggal_registrasi->format('d M Y') : '-';
            })
            ->addColumn('status_badge', function ($row) {
                // Buat HTML badge status
                $status = $row->status_complaint ?? 'N/A';
                $class = 'bg-secondary'; // Default
                if ($status == 'Baru') $class = 'bg-info';
                elseif ($status == 'Diproses') $class = 'bg-warning text-dark';
                elseif ($status == 'Selesai') $class = 'bg-success';
                elseif ($status == 'Ditolak') $class = 'bg-danger';
                return '<span class="badge ' . $class . '">' . htmlspecialchars($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                // Buat HTML tombol aksi (meniru logika view Anda)
                $buttons = [];
                $showUrl = route('feedback.show', $row->id); // Pastikan route web ada
                $editUrl = route('feedback.edit', $row->id); // Pastikan route web ada
                $deleteRoute = route('feedback.destroy', $row->id); // Route untuk form action delete

                // Contoh check Gate/Permission (sesuaikan dengan nama permission Anda)
                // if (Gate::allows('feedback_view', $row)) {
                    $buttons[] = '<a href="' . $showUrl . '" class="btn btn-info btn-sm" title="' . __('Lihat Detail') . '"><i class="fas fa-eye"></i></a>';
                // }
                // if (Gate::allows('feedback_edit', $row)) {
                    $buttons[] = '<a href="' . $editUrl . '" class="btn btn-warning btn-sm" title="' . __('Edit') . '"><i class="fas fa-edit"></i></a>';
                // }
                // if (Gate::allows('feedback_delete', $row)) {
                    // Tombol delete ini akan ditangani oleh JS di frontend (lihat contoh JS sebelumnya)
                    $buttons[] = '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'" data-route="'.$deleteRoute.'" title="' . __('Hapus') . '"><i class="fas fa-trash"></i></button>';
                // }

                return implode(' ', $buttons); // Gabungkan tombol
            })
            ->rawColumns(['status_badge', 'action']) // Beritahu DataTables kolom mana yg berisi HTML
            ->make(true); // Generate JSON response
    }

    // Tambahkan metode API lain jika perlu (store, update, destroy via API, dll.)
}