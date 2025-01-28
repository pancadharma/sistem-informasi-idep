<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class MealsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('meals_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.meals.index');
    }

    public function create()
    {
        abort_if(Gate::denies('meals_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.meals.meals_create');
    }
}
