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
            'jenis_kegiatan_id' => ['required', 'exists:trkegiatan,id'],
            'outcome_activity_id' => ['required', 'exists:trmealsqb,id'], // Jika relasinya benar
            'dusun_id' => ['required', 'exists:mdusun,id'],
            'tanggal_implementasi' => ['required', 'date'],
            'user_handler_id' => ['required', 'exists:users,id'],
            'user_compiler_id' => ['required', 'exists:users,id'],
            'score' => ['required', 'numeric', 'between:0,100'],
            'lokasi' => ['nullable', 'string', 'max:255'],
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
