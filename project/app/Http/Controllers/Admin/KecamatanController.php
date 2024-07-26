<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Requests\MassDestroyKecamatanRequest;
use App\Http\Requests\StoreKecamatanRequest;
use App\Http\Requests\UpdateKecamatanRequest;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;



class KecamatanController extends Controller
{
    public function index(){
        // abort_if(Gate::denies('kecamatan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $kabupaten = Kabupaten::all();
        $kecamatan = Kecamatan::all();
    }
}
