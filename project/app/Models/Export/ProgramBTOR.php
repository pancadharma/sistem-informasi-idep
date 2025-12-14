<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

/**
 * ProgramBTOR - Defines indirect relationship between BTOR and Program
 * 
 * This model provides methods to extract Program data for BTOR exports
 * without directly using Kegiatan.php relationships.
 */
class ProgramBTOR
{
    /**
     * Get complete program information for BTOR export
     * 
     * @param Kegiatan $kegiatan
     * @return array
     */
    public static function getProgramInfo(Kegiatan $kegiatan): array
    {
        $activity = $kegiatan->programOutcomeOutputActivity;
        $output = $activity?->program_outcome_output;
        $outcome = $output?->program_outcome;
        $program = $outcome?->program;

        return [
            'program_kode'    => $program?->kode,
            'program_nama'    => $program?->nama,
            'outcome_target'  => $outcome?->target,
            'output_target'   => $output?->target,
            'activity_target' => $activity?->target,
            'goal'            => $program?->goal?->deskripsi,
            'sdg'             => $program?->kaitanSdg?->pluck('nama')->toArray() ?? [],
        ];
    }

    /**
     * Get basic program data (code and name only)
     * 
     * @param Kegiatan $kegiatan
     * @return array
     */
    public static function getBasicProgramData(Kegiatan $kegiatan): array
    {
        $program = self::getProgram($kegiatan);

        return [
            'kode' => $program?->kode,
            'nama' => $program?->nama,
        ];
    }

    /**
     * Get program hierarchy data (Outcome, Output, Activity)
     * 
     * @param Kegiatan $kegiatan
     * @return array
     */
    public static function getHierarchyData(Kegiatan $kegiatan): array
    {
        $activity = $kegiatan->programOutcomeOutputActivity;
        $output = $activity?->program_outcome_output;
        $outcome = $output?->program_outcome;

        return [
            'outcome' => [
                'deskripsi' => $outcome?->deskripsi,
                'indikator' => $outcome?->indikator,
                'target'    => $outcome?->target,
            ],
            'output' => [
                'deskripsi' => $output?->deskripsi,
                'indikator' => $output?->indikator,
                'target'    => $output?->target,
            ],
            'activity' => [
                'kode'      => $activity?->kode,
                'nama'      => $activity?->nama,
                'deskripsi' => $activity?->deskripsi,
                'indikator' => $activity?->indikator,
                'target'    => $activity?->target,
            ],
        ];
    }

    /**
     * Get program goals and SDG relations
     * 
     * @param Kegiatan $kegiatan
     * @return array
     */
    public static function getGoalsAndSDG(Kegiatan $kegiatan): array
    {
        $program = self::getProgram($kegiatan);

        return [
            'goal' => $program?->goal?->deskripsi,
            'sdg'  => $program?->kaitanSdg?->pluck('nama')->toArray() ?? [],
        ];
    }

    /**
     * Get the Program model from Kegiatan
     * 
     * @param Kegiatan $kegiatan
     * @return \App\Models\Program|null
     */
    protected static function getProgram(Kegiatan $kegiatan)
    {
        return $kegiatan->programOutcomeOutputActivity
            ?->program_outcome_output
            ?->program_outcome
            ?->program;
    }

    /**
     * Get activity information
     * 
     * @param Kegiatan $kegiatan
     * @return array
     */
    public static function getActivityInfo(Kegiatan $kegiatan): array
    {
        $activity = $kegiatan->programOutcomeOutputActivity;

        return [
            'kode'      => $activity?->kode,
            'nama'      => $activity?->nama,
            'deskripsi' => $activity?->deskripsi,
            'indikator' => $activity?->indikator,
            'target'    => $activity?->target,
        ];
    }
}
