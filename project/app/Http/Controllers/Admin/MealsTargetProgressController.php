<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meals_Target_Progress as TargetProgress;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use \Exception;
use \Error;

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
	public function create(Request $request)
	{
		// abort_if(Gate::denies('target_progress_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$targetProgress = new TargetProgress;
		$targetProgress->program = new Program;

		if($request->isMethod('GET')){
			return view('tr.target_progress.create', compact('targetProgress'));
		}
	}

	/**
	* Store a newly created resource in storage.
	*/
	public function store(Request $request)
	{
		$targetProgress = new TargetProgress;
		$targetProgress->program = new Program;
		$detailsParams	=(object)[];

		if($request->isMethod('post')){
			try {
				// Validation
				$validated = $request->validate([
					'target_progress.program_id'					=> 'required|integer',
					'target_progress.kode_program'					=> 'required|string',
					'target_progress.tanggal'						=> 'required|date_format:d/m/Y',
	
					'target_progress.details'						=> 'array',
					// Hidden
					'target_progress.details.*.targetable_type'		=> 'required|string',
					'target_progress.details.*.targetable_id'		=> 'required|integer',
					'target_progress.details.*.level'				=> 'required|string',
					'target_progress.details.*.tipe'				=> 'required|string',

					// Numeric
					'target_progress.details.*.progress'			=> 'nullable|numeric',
					'target_progress.details.*.persentase_complete'	=> 'nullable|numeric',

					// Select
					'target_progress.details.*.status'				=> 'nullable|string',
					'target_progress.details.*.risk'				=> 'nullable|string',

					// Textarea
					'target_progress.details.*.achievements'		=> 'nullable|string',
					'target_progress.details.*.challenges'			=> 'nullable|string',
					'target_progress.details.*.mitigation'			=> 'nullable|string',
					'target_progress.details.*.notes'				=> 'nullable|string',
				]);

				$params	= $request->input('target_progress');
				if (!empty($params['tanggal'])) {
					$params['tanggal'] = Carbon::createFromFormat('d/m/Y', $params['tanggal'])
											->format('Y-m-d');
				}

				$targetProgress	= TargetProgress::create($params);
				$detailsParams	= $request->input('target_progress.details');
				$details		= $targetProgress->details()->createMany($detailsParams);

				return redirect()
					->route('target_progress.edit', $targetProgress->program_id)
					->with([
						"success"	=> true,
						"status"	=> 201,
						"message"	=> (object)['success' => ['data.stored' => ['Target progress has been saved successfully.']]],
					]);

			} catch (ValidationException $e) {
				$program_id = $targetProgress->program_id ?? $request->input('target_progress.program_id');
				$targetProgress->program_id = $program_id;
				$targetProgress->program	= Program::find($program_id);

				$params	= $request->input();

				return view('tr.target_progress.create', compact(['targetProgress', 'params']))
					->with([
							'status'			=> 422,
							'success'			=> false,
							'message'			=> (object)['error' => $e->errors()],
						]);
			} catch (QueryException $e) {
				$program_id = $targetProgress->program_id ?? $request->input('target_progress.program_id');
				$targetProgress->program_id = $program_id;
				$targetProgress->program	= Program::find($program_id);

				$params	= $request->input();

				return view('tr.target_progress.create', compact(['targetProgress', 'params']))
					->with([
						'status'  => 400,
						'success' => false,
						'message' => (object)['error' => ['database.error' => ['Database error: ' . $e->getMessage()]]],
					]);
			} catch (Exception | Error $e) {
				$program_id = $targetProgress->program_id ?? $request->input('target_progress.program_id');
				$targetProgress->program_id = $program_id;
				$targetProgress->program	= Program::find($program_id);

				$params	= $request->input();

				return view('tr.target_progress.create', compact(['targetProgress', 'params']))
					->with([
						'status'  => 500,
						'success' => false,
						'message' => (object)['error' => ['unexpected.error' => ['An unexpected error occurred: ' . $e->getMessage()]]],
					]);
			}
		}
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
	public function edit(string $program_id)
	{
		$targetProgress = TargetProgress::scopedCollection()
							->where('program_id', $program_id)
							->firstOrFail();

		return view('tr.target_progress.create', compact('targetProgress'));
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
