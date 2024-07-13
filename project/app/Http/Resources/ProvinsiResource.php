<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvinsiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        // return [
        //     "id" => $this->id,
        //     "kode" => $this->kode,
        //     "nama" => $this->nama,
        //     "aktif" => $this->aktif,
        //     "created_at" => (New Carbon($this->created_at))->format('Y-m-d H:i:s'),
        // ];
    }
}
