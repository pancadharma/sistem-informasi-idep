<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
// use App\Http\Requests\MassDestroyProvinsiRequest;
use App\Http\Requests\StoreCountryRequest;
// use App\Http\Requests\UpdateProvinsiRequest;
use App\Models\Country;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CountryCountroller extends Controller
{
    use MediaUploadingTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('country_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::get();
        return $countries;
        return view('master.country', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request)
    {
        // Create a new Country record
        $country = Country::create($request->validated());

        // Handle logo upload if it exists
        if ($request->hasFile('flag')) {
            // Store the uploaded logo in public/images directory
            $path = $request->file('flag')->store('images/flag', 'public');
            $country->addMedia(storage_path('app/public/' . $path))->toMediaCollection('flag');
        }

        // Handle ck-media if it exists
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $country->id]);
        }

        // Redirect to the provinsi index page with a success message
        return redirect()->route('country')->with('success', 'Country created successfully.');
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
