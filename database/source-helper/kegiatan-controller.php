<?php

use Carbon\Carbon;

public function list_kegiatan(Request $request)
{
    if (!$request->ajax() && !$request->isJson()) {
        return "Not an Ajax Request & JSON REQUEST";
    }

    $kegiatan = Kegiatan::with([
            'dusun',
            'users',
            'kategori_lokasi',
            'activity.program_outcome_output.program_outcome.program',
            'satuan',
            'jenis_bantuan'
        ])
        ->select('trkegiatan.*')
        ->get()
        ->map(function ($item) {
            // Format the dates using Carbon
            $item->tanggalmulai = Carbon::parse($item->tanggalmulai)->format('d-m-Y'); // Customize the format as needed
            $item->tanggalselesai = Carbon::parse($item->tanggalselesai)->format('d-m-Y'); // Customize the format as needed
            
            // Add duration in days
            $item->duration_in_days = $item->getDurationInDays();

            return $item;
        });

    $data = DataTables::of($kegiatan)
        ->addIndexColumn()
        ->addColumn('program_name', function ($kegiatan) {
            return $kegiatan->activity->program_outcome_output->program_outcome->program->nama ?? 'N/A';
        })
        ->addColumn('duration_in_days', function ($kegiatan) {
            return $kegiatan->duration_in_days ?? 'N/A';
        })
        ->addColumn('action', function ($kegiatan) {
            $buttons = [];

            if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_edit')) {
                $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', __('global.edit') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
            }
            if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_view') || auth()->user()->can('kegiatan_access')) {
                $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', __('global.view') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
            }
            if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_details_edit') || auth()->user()->can('kegiatan_edit')) {
                $buttons[] = $this->generateButton('details', 'danger', 'list-ul', __('global.details') . __('cruds.kegiatan.label') . $kegiatan->nama, $kegiatan->id);
            }
            return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
        })
        ->rawColumns(['action'])
        ->make(true);

    return $data;
}
?>