<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MealsTargetProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // abort_if(Gate::denies('target_progress_access'), Response::HTTP_FORBIDDEN, '403 Forbidden'); // Meals Target & Progress Policy - Permission
        return view('tr.target_progress.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // abort_if(Gate::denies('target_progress_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.target_progress.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
