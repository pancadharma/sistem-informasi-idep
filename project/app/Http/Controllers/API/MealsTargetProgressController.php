<?php

namespace App\Http\Controllers\API;

use App\Enums\TargetProgressRisk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meals_Target_Progress as TargetProgress;
use App\Models\Meals_Target_Progress_Detail as TargetProgressDetail;
use App\Enums\TargetProgressStatus as StatusOptions;
use App\Enums\TargetProgressRisk as RiskOptions;
use Yajra\DataTables\DataTables;
use App\Models\Program;
use Carbon\Carbon;
use Ramsey\Uuid\Type\Time;
use Throwable;

class MealsTargetProgressController extends Controller
{
	/**
	* Index
	* Display a listing of the resource.
	**/
	public function getTargetProgress(Request $request)
	{
		// if (!$request->ajax() && !$request->isJson()) {
		//     return "Not an Ajax Request & JSON REQUEST";
		// }

		$targetProgress = TargetProgress::scopedCollection();

		return DataTables::of($targetProgress)
			->addIndexColumn()
			->addColumn('kode_program', function ($target) {
				return $target->program->kode;
			})
			->addColumn('nama_program', function ($target) {
				return $target->program->nama;
			})
			->addColumn('tanggal', function ($target) {
				return $target->tanggal->format('d M Y');
			})
			->addColumn('updated_count', function ($target) {
				return $target->updated_count;
			})
			->addColumn('action', function ($target) {
				$edit_url = route('target_progress.edit', $target->program_id);
				$button = "
					<div class='button-container'>
						<a href='{$edit_url}' class='btn btn-sm btn-info edit-target-progress'>
							<i class='bi bi-pencil-square'></i>
						</a>
						<button type='button' class='btn btn-sm btn-secondary target-progress-history'>
							<i class='fas fa-history'></i>
						</button>
					</div>
				";

				return $button;
			})
			->rawColumns([
				'action',
			])
			->make(true);
	}

	/**
	* Modal pilih program yang belum memiliki target progress.
	**/
	public function getPrograms(Request $request)
	{
		if ($request->ajax()) {
			$unhandledPrograms = Program::withTargetsProgress()
				->whereDoesntHave('targetProgresses')
				->get();

			return DataTables::of(source: $unhandledPrograms)
				->addColumn('target', function ($row) {
					$goal_targets       = $this->splitupTargets($row->goal?->target);
					$objektif_targets   = $this->splitupTargets($row->objektif?->target);
					$outcome_targets    = [];
					$output_targets     = [];
					$activity_targets   = [];

					foreach ($row->outcome as $outcome) {
						$outcome_targets = array_merge($outcome_targets, $this->splitupTargets($outcome?->target));
						foreach ($outcome->output as $output) {
							$output_targets = array_merge($output_targets, $this->splitupTargets($output?->target));
							foreach ($output->activities as $activity) {
								$activity_targets = array_merge($activity_targets, $this->splitupTargets($activity?->target));
							}
						}
					}

					$targets = [
						...$goal_targets,
						...$objektif_targets,
						...$outcome_targets,
						...$output_targets,
						...$activity_targets,
					];

					return count($targets);
				})
				->addColumn('action', function ($row) {
					$button = "
								<button
									type='button'
									class='btn btn-sm btn-danger select-program'
									data-id='$row->id'
									data-kode='$row->kode'
									data-nama='$row->nama'
								>
									<i class='bi bi-plus'></i>
								</button>
							";
					
					return $button;
				})
				->rawColumns([
					'action',
				])
				->make(true);
		}
	}

	/**
	* Modal tampilkan target progress history yang dari suatu program.
	**/
	public function getHistories(Request $request)
	{
		if ($request->ajax()) {
			$query = TargetProgress::withDetails()
				->where('program_id', request('id'))
				->orderBy('created_at', 'desc')
				->get();

			$recent_history = TargetProgress::scopedCollection()
								->where('program_id', request('id'))
								->first();

			return DataTables::of($query)
				->addColumn('raw_tanggal', function ($row) {
					return $row->tanggal->format("Y/m/d");
				})
				->addColumn('tanggal', function ($row) {
					return "<span class='text-nowrap px-1'>{$row->tanggal->format("d F Y")}</span>";
				})
				->addColumn('waktu', function ($row) {
					return $row->created_at->format('d/m/Y H:i');
				})
				->addColumn('kode', function ($row) {
					return $row->program->kode;
				})
				->addColumn('nama', function ($row) {
					return $row->program->nama;
				})
				->addColumn('target', function ($row) {
					return $row->details_count;
				})
				->addColumn('recent_history', function ($row) use ($recent_history) {
					return $row->id == $recent_history->id;
				})
				->addColumn('action', function ($row) {
					$button = "
							<button
								type='button'
								class='btn btn-sm btn-info show-target-progress-history'
							>
								<i class='bi bi-eye'></i>
							</button>
						";
					
					return $button;
				})
				->rawColumns([
					'tanggal',
					'waktu',
					'action',
				])
				->make(true);
		}
	}

	/**
	* Modal tampilkan target progress dari suatu program.
	**/
	public function getTargets(Request $request)
	{
		if ($request->ajax()) {
			$tanggal	= Carbon::createFromFormat('d/m/Y', request('tanggal') ?: now()->format('d/m/Y'))->format('Y-m-d');
			$program_id	= request('program_id');

			// Check Program
			$program = Program::withTargetsProgress()->find($program_id);

			// Edit TargetProgress
			$targetProgress = TargetProgress::scopedCollection()
								->with(['details:*'])
								->firstOrNew([
									'program_id'	=> $program?->id,
									'tanggal'		=> $tanggal,
								]);

			$targets = $this->generateTargets($targetProgress);
			// Handle Params
			$targets = collect(array_map(function($target, $form_idx){
				$target->params = $params = (object)[
					'level'					=> $this->getPramName('level', $form_idx),
					'tipe'					=> $this->getPramName('tipe', $form_idx),
					'targetable_id'			=> $this->getPramName('targetable_id', $form_idx),
					'targetable_type'		=> $this->getPramName('targetable_type', $form_idx),
					'achievements'			=> $this->getPramName('achievements', $form_idx),
					'progress'				=> $this->getPramName('progress', $form_idx),
					'persentase_complete'	=> $this->getPramName('persentase_complete', $form_idx),
					'status'				=> $this->getPramName('status', $form_idx),
					'challenges'			=> $this->getPramName('challenges', $form_idx),
					'mitigation'			=> $this->getPramName('mitigation', $form_idx),
					'risk'					=> $this->getPramName('risk', $form_idx),
					'notes'					=> $this->getPramName('notes', $form_idx),
				];

				$persentase_complete = $target->detail->persentase_complete ? (int)$target->detail->persentase_complete : null;

				$target->achievements			= "<textarea class='form-control' name='{$params->achievements}' rows='1' style='min-width:200px; height:100%;'>{$target->detail->achievements}</textarea>";
				$target->progress				= "<div class='input-group'><input class='form-control mw-100-px' type='number' min='0' max='100' name='{$params->progress}' value='{$target->detail->progress}'><div class='input-group-append'><div class='input-group-text'>%</div></div></div>";
				$target->persentase_complete	= "<div class='input-group'><input class='form-control mw-100-px' type='number' min='0' max='100' name='{$params->persentase_complete}' value='{$persentase_complete}'><div class='input-group-append'><div class='input-group-text'>%</div></div></div>";
				$target->status					= "<select name='{$params->status}'><option selected value='{$target->detail->status}'>{$target->detail->status?->text()}</option></select>";
				$target->challenges				= "<textarea class='form-control' name='{$params->challenges}' rows='1' style='min-width:200px; height:100%;'>{$target->detail->challenges}</textarea>";
				$target->mitigation				= "<textarea class='form-control' name='{$params->mitigation}' rows='1' style='min-width:200px; height:100%;'>{$target->detail->mitigation}</textarea>";
				$target->risk					= "<select name='{$params->risk}' value='{$target->detail->risk}'><option selected value='{$target->detail->risk}'>{$target->detail->risk?->text()}</option></select>";
				$target->notes					= "<textarea class='form-control' name='{$params->notes}' rows='1' style='min-width:200px; height:100%;'>{$target->detail->notes}</textarea>";

				return $target;
			}, $targets, array_keys($targets)));

			return DataTables::of($targets)
				->addIndexColumn()
				->addColumn('level', function ($target) {
					return "
						{$target->level_text}
						<input type='hidden' name='{$target->params->level}' value='{$target->level}'>
						<input type='hidden' name='{$target->params->tipe}' value='{$target->tipe}'>
						<input type='hidden' name='{$target->params->targetable_id}' value='{$target->targetable_id}'>
						<input type='hidden' name='{$target->params->targetable_type}' value='{$target->targetable_type}'>
					";
				})
				->addColumn('deskripsi', function ($target) {
					return $target->deskripsi;
				})
				->addColumn('indikator', function ($target) {
					return $target->indikator;
				})
				->addColumn('target', function ($target) {
					return $target->target;
				})
				->addColumn('achievements', function ($target) {
					return $target->achievements;
				})
				->addColumn('progress', function ($target) {
					return $target->progress;
				})
				->addColumn('persentase_complete', function ($target) {
					return $target->persentase_complete;
				})
				->addColumn('status', function ($target) {
					return $target->status;
				})
				->addColumn('challenges', function ($target) {
					return $target->challenges;
				})
				->addColumn('mitigation', function ($target) {
					return $target->mitigation;
				})
				->addColumn('risk', function ($target) {
					return $target->risk;
				})
				->addColumn('notes', function ($target) {
					return $target->notes;
				})
				->addColumn('target_id', function ($target) {
					return $target->target_id;
				})
				->addColumn('parent_target_id', function ($target) {
					return $target->parent_target_id;
				})
				->addColumn('indent', function ($target) {
					return $target->indent;
				})
				->rawColumns([
					'level',
					'achievements',
					'progress',
					'persentase_complete',
					'status',
					'challenges',
					'mitigation',
					'risk',
					'notes',
				])
				->make(true);
		}
	}

	/**
	* Modal tampilkan history target progress dari suatu program.
	**/
	public function showTargets(Request $request)
	{
		if ($request->ajax()) {
			$target_progress_id	= request('target_progress_id');
			$targetProgress = TargetProgress::with(['details:*'])
								->findOrFail($target_progress_id);

			$targets = $this->generateTargets($targetProgress);
			return DataTables::of($targets)
					->addIndexColumn()
					->addColumn('level', function ($target) {
						return $target->level_text;
					})
					->addColumn('deskripsi', function ($target) {
						return $target->deskripsi;
					})
					->addColumn('indikator', function ($target) {
						return $target->indikator;
					})
					->addColumn('target', function ($target) {
						return $target->target;
					})
					->addColumn('achievements', function ($target) {
						return $target->detail->achievements;
					})
					->addColumn('progress', function ($target) {
						if($target->detail->progress){
							return "{$target->detail->progress}%";
						}else{
							return "-";
						}
					})
					->addColumn('persentase_complete', function ($target) {
						if($target->detail->persentase_complete){
							$complete = (int)$target->detail->persentase_complete;
							return "{$complete}%";
						}else{
							return "-";
						}
					})
					->addColumn('status', function ($target) {
						if($target->detail->status){
							return "<span class='target-progress-status opt-{$target->detail->status}'>{$target->detail->status?->text()}</span>";
						}else{
							return "-";
						}
					})
					->addColumn('challenges', function ($target) {
						return $target->detail->challenges;
					})
					->addColumn('mitigation', function ($target) {
						return $target->detail->mitigation;
					})
					->addColumn('risk', function ($target) {
						if($target->detail->risk){
							return "<span class='target-progress-risk opt-{$target->detail->risk}'>{$target->detail->risk?->text()}</span>";
						}else{
							return "-";
						}
					})
					->addColumn('notes', function ($target) {
						return $target->detail->notes;
					})
					->addColumn('target_id', function ($target) {
						return $target->target_id;
					})
					->addColumn('parent_target_id', function ($target) {
						return $target->parent_target_id;
					})
					->addColumn('indent', function ($target) {
						return $target->indent;
					})
					->rawColumns([
						'level',
						'achievements',
						'progress',
						'persentase_complete',
						'status',
						'challenges',
						'mitigation',
						'risk',
						'notes',
					])
					->make(true);
		}
	}

	/**
	* SELECT2 Ajax Options
	*   - TargetProgressStatus
	*   - TargetProgressRisk
	*/
	public function getStatusOptions(Request $request)
	{
		$query = strtolower($request->get('q', ''));
		$options = collect(StatusOptions::asSelectArray())
			->filter(function ($label, $value) use ($query) {
				return stripos($label, $query) !== false;
			})
			->map(function ($label, $value) {
				return [
					'id' => $value,   // Select2 expects 'id' for value
					'text' => $label, // and 'text' for label
				];
			})
			->values();

		return response()->json($options);
	}

	public function getRiskOptions(Request $request)
	{
		$query = strtolower($request->get('q', ''));
		$options = collect(RiskOptions::asSelectArray())
			->filter(function ($label, $value) use ($query) {
				return stripos($label, $query) !== false;
			})
			->map(function ($label, $value) {
				return [
					'id'	=> $value,	// Select2 expects 'id' for value
					'text'	=> $label,	// and 'text' for label
				];
			})
			->values();

		return response()->json($options);
	}

	/**
	* PRIVATE Functions
	*/

	private function generateTargets(object $targetProgress){
		$program			= $targetProgress->program;
		$goal_targets       = $this->splitupTargetsObjects($targetProgress, 1, $program?->goal, "Goal");
		$objektif_targets   = $this->splitupTargetsObjects($targetProgress, 1, $program?->objektif, "Objektif");
		$outcome_targets    = [];
		$output_targets     = [];
		$activity_targets   = [];

		$targets = array_merge($goal_targets, $objektif_targets);
		foreach ($program?->outcome ?? [] as $oc_idx => $outcome) {
			$oc_targets         = $this->splitupTargetsObjects($targetProgress, 1, $outcome, "Outcome", $oc_idx+1, null, null, true);
			$outcome_targets    = array_merge($outcome_targets, $oc_targets);
			$targets            = array_merge($targets, $oc_targets);
			foreach ($outcome->output as $op_idx => $output) {
				$op_targets     = $this->splitupTargetsObjects($targetProgress, 2, $output, "Output", $op_idx+1, "Outcome", $oc_idx+1);
				$output_targets = array_merge($output_targets, $op_targets);
				$targets        = array_merge($targets, $op_targets);
				foreach ($output->activities as $av_idx => $activity) {
					$av_targets         = $this->splitupTargetsObjects($targetProgress, 3, $activity, "Activity", $av_idx+1, "Output", ($oc_idx+1).'.'.($op_idx+1));
					$activity_targets   = array_merge($activity_targets, $av_targets);
					$targets            = array_merge($targets, $av_targets);
				}
			}
		}

		return $targets;
	}

	private function splitupTargets(?string $target): array
	{
		$target = $target ?? '';
		return preg_split('/\r\n|\r|\n/', $target, flags: PREG_SPLIT_NO_EMPTY);
	}

	/*
	* Slit Up Each Targets Array Element to Object
	*
	* ↳ Mengkonfersi tiap element array menjadi object
	*
	* Object Dapat Berupa:
	*	- Goal		-> deskripsi, indikator, target
	*	- Objektif	-> deskripsi, indikator, target
	*	- Outcome	-> deskripsi, indikator, target
	*	- Output	-> deskripsi, indikator, target
	*	- Kegiatan	-> deskripsi, indikator, target
	*/
	private function splitupTargetsObjects(?object $targetProgress, ?int $indent=1, ?object $object, string $type="", ?string $idx=null, ?string $parentType=null, ?string $parentIdx=null, ?bool $bold=false):array{
		$targets	= $this->splitupTargets($object?->target);
		$id			= collect([$parentIdx, $idx])->filter()->join('.');
		
		return array_map(function($target, $tg_idx) use ($targetProgress, $indent, $parentType, $parentIdx, $object, $type, $id, $bold){
			$level		= empty($id) ? $tg_idx+1 : $id;
			$level_text	= collect([$type, $level])->filter()->join(' ');

			$type		= strtolower($type);
			$parentType	= strtolower($parentType ?: "");
			$target_id	= str_replace('.', '_', $type . "_" . $level);
			$parentId	= str_replace('.', '_', $parentType . "_" . $parentIdx);

			$attributes = (object)[
				'record'			=> null,
				'indent'			=> $indent,
				'level'				=> $level,
				'level_text'		=> $bold ? "<b>{$level_text}</b>" : $level_text,

				'tipe'				=> $type,
				'target_id'			=> $target_id,
				'parent_target_id'	=> $parentIdx ? $parentId : null,

				'targetable_id'		=> $object->id,
				'targetable_type'	=> get_class($object),

				'deskripsi'			=> $object->deskripsi,
				'indikator'			=> $object->indikator,
				'target'			=> $target,
			];

			$details = $targetProgress->details()
				->where([
					'level'             => $attributes->level,
					'tipe'              => $attributes->tipe,
					'targetable_id'     => $attributes->targetable_id,
					'targetable_type'   => $attributes->targetable_type,
				])
				->get()
				->values();
		
			try {
				$detail = $details->get($tg_idx) ?? $details->first();
			} catch (Throwable $th) {
				$detail = null;
			}
			
			// Fallback: make a new model if nothing found
			if (!$detail || !$detail->exists) {
				$detail = $targetProgress->details()->make([
					'level'             => $attributes->level,
					'tipe'              => $attributes->tipe,
					'targetable_id'     => $attributes->targetable_id,
					'targetable_type'   => $attributes->targetable_type,
				]);
			}		

			$attributes->detail = $detail;

			return $attributes;
		}, $targets, array_keys($targets));
	}

	private function getPramName(?string $attribute="", ?string $index="0", ?bool $multiple=false) : string {
		$base   = "target_progress[details][$index]";
		$name   = $attribute ? "[$attribute]" : '';
		$suffix = $multiple ? '[]' : '';
		
		return $base . $name . $suffix;
	}
}
