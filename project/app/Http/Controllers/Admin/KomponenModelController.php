<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\KomponenModel;
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


    // public function getSektor(Request $request)
    // {
    //     $request->validate([
    //         'search'    => 'nullable|string|max:255',
    //         'page'      => 'nullable|integer|min:1',
    //         'id'        => 'nullable|array|min:1',
    //         'id.*'      => 'integer',
    //     ]);

    //     $search = $request->input('search', '');
    //     $page = $request->input('page', 1);
    //     $ids = $request->input('id', []);

    //     if (!is_array($ids) && $ids !== null) {
    //         $ids = [$ids];
    //     }

    //     // $data = mSektor::when(!empty($ids), function ($query) use ($ids) {
    //     $data = TargetReinstra::when(!empty($ids), function ($query) use ($ids) {
    //         return $query->whereIn('id', $ids);
    //     }, function ($query) use ($search) {
    //         return $query->where('nama', 'like', "%{$search}%");
    //     });

    //     $data = $data->paginate(20, ['*'], 'page', $page);
    //     return response()->json($data);
    // }

    public function getSektor(Request $request)
    {
    $request->validate([
        'search'    => 'nullable|string|max:255',
        'page'      => 'nullable|integer|min:1',
        'id'        => 'nullable|array|min:1',
        'id.*'      => 'integer',
    ]);

    $search = $request->input('search', '');
    $page = $request->input('page', 1);
    $ids = $request->input('id', []);

    if (!is_array($ids) && $ids !== null) {
        $ids = [$ids];
    }

    // Ambil data dari TargetReinstra
    $query = TargetReinstra::when(!empty($ids), function ($q) use ($ids) {
        return $q->whereIn('id', $ids);
    }, function ($q) use ($search) {
        return $q->where('nama', 'like', "%{$search}%");
    });

    // Ambil data sesuai Select2
    $data = $query->select('id', 'nama')->paginate(20, ['*'], 'page', $page);

    return response()->json([
        'results' => $data->items(), // hanya ambil data
        'pagination' => [
            'more' => $data->hasMorePages() // untuk infinite scroll select2
        ]
    ]);
    }

    public function getModel(Request $request)
    {
    $request->validate([
        'search'    => 'nullable|string|max:255',
        'page'      => 'nullable|integer|min:1',
        'id'        => 'nullable|array|min:1',
        'id.*'      => 'integer',
    ]);

    $search = $request->input('search', '');
    $page = $request->input('page', 1);
    $ids = $request->input('id', []);

    if (!is_array($ids) && $ids !== null) {
        $ids = [$ids];
    }

    // Ambil data dari TargetReinstra
    $query = KomponenModel::when(!empty($ids), function ($q) use ($ids) {
        return $q->whereIn('id', $ids);
    }, function ($q) use ($search) {
        return $q->where('nama', 'like', "%{$search}%");
    });

    // Ambil data sesuai Select2
    $data = $query->select('id', 'nama')->paginate(20, ['*'], 'page', $page);

    return response()->json([
        'results' => $data->items(), // hanya ambil data
        'pagination' => [
            'more' => $data->hasMorePages() // untuk infinite scroll select2
        ]
    ]);
    }
}
