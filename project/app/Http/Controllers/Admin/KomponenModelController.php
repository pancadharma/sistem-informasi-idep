<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\TargetReinstra;
use Illuminate\Support\Facades\Gate;

class KomponenModelController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('komponenmodel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.komponenmodel.index');
    }

    public function create()
    {
        abort_if(Gate::denies('komponenmodel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.komponenmodel.create');
    }
}
