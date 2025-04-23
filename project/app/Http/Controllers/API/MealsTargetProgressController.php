<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meals_Target_Progress;
use Illuminate\Http\Request;
use App\Models\Meals_Target_Progress_Detail as TargetProgress;
use Yajra\DataTables\DataTables;
use App\Models\Program;

class MealsTargetProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDataTable(Request $request)
    {
        // if (!$request->ajax() && !$request->isJson()) {
        //     return "Not an Ajax Request & JSON REQUEST";
        // }

        $kegiatan = TargetProgress::all();

        $data = DataTables::of($kegiatan)
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);

        return $data;
    }

    private function programsWithTargets(){
        return Program::with(relations: [
            'goal:id,program_id,deskripsi,indikator,target',
            'objektif:id,program_id,deskripsi,indikator,target',
            'outcome' => function ($query) {
                $query->select('id', 'program_id', 'deskripsi', 'indikator', 'target');
            },
            'outcome.output' => function ($query) {
                $query->select('id', 'programoutcome_id', 'deskripsi', 'indikator', 'target');
            },
            'outcome.output.activities' => function ($query) {
                $query->select('id', 'programoutcomeoutput_id', 'deskripsi', 'indikator', 'target');
            },
        ]);
    }

    public function getPrograms(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->programsWithTargets()->get();

            return DataTables::of(source: $query)
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
                ->rawColumns(['action', 'activities'])
                ->make(true);
        }
    }

    public function getTargets(Request $request)
    {
        if ($request->ajax()) {
            $program = $this->programsWithTargets()
                          ->where('id', request('id'))
                          ->first();

            $goal_targets       = $this->splitupTargetsObjects($program?->goal, "Goal");
            $objektif_targets   = $this->splitupTargetsObjects($program?->objektif, "Objektif");
            $outcome_targets    = [];
            $output_targets     = [];
            $activity_targets   = [];

            $targets = array_merge($goal_targets, $objektif_targets);
            foreach ($program?->outcome ?? [] as $oc_idx => $outcome) {
                $oc_targets         = $this->splitupTargetsObjects($outcome, "Outcome", $oc_idx+1, null, true);
                $outcome_targets    = array_merge($outcome_targets, $oc_targets);
                $targets            = array_merge($targets, $oc_targets);
                foreach ($outcome->output as $op_idx => $output) {
                    $op_targets     = $this->splitupTargetsObjects($output, "Output", $op_idx+1, $oc_idx+1);
                    $output_targets = array_merge($output_targets, $op_targets);
                    $targets        = array_merge($targets, $op_targets);
                    foreach ($output->activities as $av_idx => $activity) {
                        $av_targets         = $this->splitupTargetsObjects($activity, "Activity", $av_idx+1, ($oc_idx+1).'.'.($op_idx+1));
                        $activity_targets   = array_merge($activity_targets, $av_targets);
                        $targets            = array_merge($targets, $av_targets);
                    }
                }
            }

            $targets = array_map(function($target, $form_idx){
                $params = (object)[
                    'id_target'             => $this->getPramName('id_target', $form_idx),
                    'achievements'          => $this->getPramName('achievements', $form_idx),
                    'progress'              => $this->getPramName('progress', $form_idx),
                    'persentase_complete'   => $this->getPramName('persentase_complete', $form_idx),
                    'status'                => $this->getPramName('status', $form_idx),
                    'challenges'            => $this->getPramName('challenges', $form_idx),
                    'mitigation'            => $this->getPramName('mitigation', $form_idx),
                    'risk'                  => $this->getPramName('risk', $form_idx),
                    'notes'                 => $this->getPramName('notes', $form_idx),
                    'tipe'                  => $this->getPramName('tipe', $form_idx),
                ];

                $target->achievements          = "<div class='input-group'><input class='form-control mw-100-px' type='number' name='{$params->achievements}'><div class='input-group-append'><div class='input-group-text'>%</div></div></div>";
                $target->progress              = "<div class='input-group'><input class='form-control mw-100-px' type='number' name='{$params->progress}'><div class='input-group-append'><div class='input-group-text'>%</div></div></div>";
                $target->persentase_complete   = "<div class='input-group'><input class='form-control mw-100-px' type='number' name='{$params->persentase_complete}'><div class='input-group-append'><div class='input-group-text'>%</div></div></div>";
                $target->status                = "<select name='{$params->status}'></select>";
                $target->challenges            = "<input class='form-control' type='text' name='{$params->challenges}'>";
                $target->mitigation            = "<input class='form-control' type='text' name='{$params->mitigation}'>";
                $target->risk                  = "<select name='{$params->risk}'></select>";
                $target->notes                 = "<textarea class='form-control' name='{$params->notes}' row='3' style='min-width:200px; height:100%;'></textarea>";
                $target->tipe                  = "<input class='form-control' type='text' name='{$params->tipe}' value='{$target->tipe}'>";

                return $target;
            }, $targets, array_keys($targets));

            return DataTables::of(source: $targets)
                ->addIndexColumn()
                ->addColumn('level', function ($target) {
                    return $target->level;
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
                ->addColumn('tipe', function ($target) {
                    return $target->tipe;
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
                    'tipe',
                ])
                ->make(true);
        }
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
    *   - Goal      -> deskripsi, indikator, target
    *   - Objektif  -> deskripsi, indikator, target
    *   - Outcome   -> deskripsi, indikator, target
    *   - Output    -> deskripsi, indikator, target
    *   - Kegiatan  -> deskripsi, indikator, target
    */

    private function splitupTargetsObjects(?object $object, string $type="", ?string $idx=null, ?string $parentIdx=null, ?bool $bold=false):array{
        $targets = $this->splitupTargets($object?->target);
        $keyMap = collect([$parentIdx, $idx])->filter()->join('.');

        return array_map(function($target, $tg_idx) use ($object, $type, $keyMap, $bold){
            $level = collect([$type, (empty($keyMap) ? $tg_idx+1 : $keyMap)])->filter()->join(' ');
            if($bold){
                $level = "<b>$level</b>";
            }

            return (object)[
                'level' => $level,
                'deskripsi' => $object->deskripsi,
                'indikator' => $object->indikator,
                'target'    => $target,
                'tipe'      => $type,
            ];
        }, $targets, array_keys($targets));
    }

    private function getPramName(?string $attribute="", ?string $index="0", ?bool $multiple=false) : string {
        $base   = "target_progress[details][$index]";
        $name   = $attribute ? "[$attribute]" : '';
        $suffix = $multiple ? '[]' : '';

        return $base . $name . $suffix;
    }
}
