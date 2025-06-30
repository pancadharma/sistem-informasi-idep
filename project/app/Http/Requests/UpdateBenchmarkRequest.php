<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBenchmarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return  auth()->user()->id === 1 || auth()->user()->can('benchmark_create') || Gate::allows('benchmark_create');
    }

    public function rules(): array
    {
        return [
            'program_id' => ['required', 'exists:trprogram,id'],
            'jeniskegiatan_id' => ['required', 'exists:mjeniskegiatan,id'],
            'kegiatan_id' => ['required', 'exists:trkegiatan,id'],
            'desa_id' => ['required', 'exists:kelurahan,id'],
            'kecamatan_id' => ['required', 'exists:kecamatan,id'],
            'provinsi_id' => ['required', 'exists:provinsi,id'],
            'kabupaten_id' => ['required', 'exists:kabupaten,id'],
            'tanggalimplementasi' => ['required', 'date'],
            'handler' => ['required'],
            'usercompiler_id' => ['required', 'exists:users,id'],
            'score' => ['required', 'numeric', 'between:0,100'],
            'catatanevaluasi' => ['nullable', 'string'],
            'area' => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'program_id.required' => 'Program harus diisi.',
            'program_id.exists' => 'Program tidak ditemukan.',
            'jeniskegiatan_id.required' => 'Jenis kegiatan harus diisi.',
            'jeniskegiatan_id.exists' => 'Jenis kegiatan tidak valid.',
            'kegiatan_id.required' => 'Kegiatan harus diisi.',
            'kegiatan_id.exists' => 'Kegiatan tidak valid.',
            'desa_id.required' => 'Desa harus diisi.',
            'desa_id.exists' => 'Desa tidak valid.',
            'tanggalimplementasi.required' => 'Tanggal implementasi harus diisi.',
            'tanggalimplementasi.date' => 'Tanggal tidak valid.',
            'handler.required' => 'Handler harus diisi.',
            'usercompiler_id.required' => 'Compiler harus diisi.',
            'score.required' => 'Skor harus diisi.',
            'score.numeric' => 'Skor harus berupa angka.',
            'score.between' => 'Skor harus antara 0 sampai 100.',
        ];
    }
}
