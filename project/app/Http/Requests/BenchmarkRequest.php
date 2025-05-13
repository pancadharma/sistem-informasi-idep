<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BenchmarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('meals_quality_benchmark_create');
    }

    public function rules(): array
    {
        return [
            'program_id' => ['required', 'exists:trprogram,id'],
            'jenis_kegiatan_id' => ['required', 'exists:mjeniskegiatan,id'],
            'kegiatan_id' => ['required', 'exists:trkegiatan,id'], // Jika relasinya benar
            'desa_id' => ['required', 'exists:kelurahan,id'],
            'kecamatan_id' => ['required', 'exists:kecamatan,id'],
            'provinsi_id' => ['required', 'exists:provinsi,id'],
            'kabuoaten_id' => ['required', 'exists:kabupaten,id'],
            'tanggal_implementasi' => ['required', 'date'],
            'handler' => ['required'],
            'user_compiler_id' => ['required', 'exists:users,id'],
            'score' => ['required', 'numeric', 'between:0,100'],
            'catatan_evaluasi' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'program_id.required' => 'Program harus diisi.',
            'program_id.exists' => 'Program tidak ditemukan.',
            'jenis_kegiatan_id.required' => 'Jenis kegiatan harus diisi.',
            'jenis_kegiatan_id.exists' => 'Jenis kegiatan tidak valid.',
            'outcome_activity_id.required' => 'Outcome activity harus diisi.',
            'outcome_activity_id.exists' => 'Outcome activity tidak valid.',
            'dusun_id.required' => 'Dusun harus diisi.',
            'dusun_id.exists' => 'Dusun tidak valid.',
            'tanggal_implementasi.required' => 'Tanggal implementasi harus diisi.',
            'tanggal_implementasi.date' => 'Tanggal tidak valid.',
            'user_handler_id.required' => 'Handler harus diisi.',
            'user_compiler_id.required' => 'Compiler harus diisi.',
            'score.required' => 'Skor harus diisi.',
            'score.numeric' => 'Skor harus berupa angka.',
            'score.between' => 'Skor harus antara 0 sampai 100.',
        ];
    }
}
