<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [];
        $title = 'Home Dashboard';
        $name = 'I Gede Adi Surya Eka Pramana Putra';
        return view('home', compact('title', 'name'));
    }

    public function show(){

    }
}
