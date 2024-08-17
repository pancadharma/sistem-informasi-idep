<?php
function getDesa(Request $request) {
        if ($request->ajax()) {
            $query = Kelurahan::select(
                'kelurahan.id',
                'kelurahan.kode',
                'kelurahan.nama',
                'kelurahan.aktif',

                'kecamatan.id AS kecamatan_id',
                'kecamatan.nama AS kecamatan_nama',
                
                'kabupaten.id AS kabupaten_id',
                'kabupaten.nama AS kabupaten_nama'
            )
            ->join('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->join('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id');           
            $searchValue = $request->input('search.value'); // Directly retrieve the value
            if (!empty($searchValue)) {
                $query->where(function($q) use ($searchValue) {
                        $q->where('kelurahan.nama', 'like', "%{$searchValue}%")
                          ->orWhere('kecamatan.nama', 'like', "%{$searchValue}%")
                          ->orWhere('kelurahan.kode', 'like', "%{$searchValue}%");
                    });
                }
            //alternative query
            // $query = Kelurahan::select('kelurahan.id', 'kelurahan.kode', 'kelurahan.nama', 'kelurahan.aktif', 'kelurahan.kecamatan_id')->with('kecamatan:id,nama');
            $data = DataTables::of($query)
            ->addColumn('action', function ($desa) {
                return '<button type="button" class="btn btn-sm btn-info edit-kec-btn" data-action="edit" 
                data-kecamatan-id="'. $desa->id .'" title="'.__('global.edit') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
                <i class="fas fa-pencil-alt"></i> Edit</button>              
                <button type="button" class="btn btn-sm btn-primary view-kec-btn" data-action="view"
                data-kecamatan-id="'. $desa->id .'" value="'. $desa->id .'" title="'.__('global.view') .' '. __('cruds.kecamatan.title') .' '. $desa->nama .'">
                <i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
            return $data;            
        }
    }