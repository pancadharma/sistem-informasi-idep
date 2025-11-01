<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KegiatanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama_kegiatan' => $this->activity->nama,
            'user_id' => $this->user_id,
            'user_nama' => $this->users->nama,
            'tanggalmulai' => (new Carbon($this->tanggalmulai))->format('Y-m-d H:i:s'),
            'tanggalselesai' => (new Carbon($this->tanggalselesai))->format('Y-m-d H:i:s'),
            'status' => $this->status,

            'jenis_kegiatan_id' => $this->jenis_kegiatan_id,

            'program' => new ProgramResource(optional(
                $this->programOutcomeOutputActivity?->program_Outcome_Output?->program_Outcome?->program
            )),

            'penulis' => $this->kegiatan_penulis->map(fn($pivot) => [
                'user_id' => $pivot->user?->id,
                'user_nama' => $pivot->user?->nama,
                'peran_id' => $pivot->peran_id,
                'peran_nama' => $pivot->peran?->nama,
            ]),

        ];
    }
}
