<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Kegiatan_Mitra;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Admin\KegiatanController as KegiatanMainController;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class KegiatanControllerBeta extends Controller
{
    /**
     * Show Kegiatan detail for the redesigned Blade view.
     */
    public function show(int $id): View
    {
        $kegiatan = Kegiatan::with([
            // Program chain
            'programOutcomeOutputActivity.program_outcome_output.program_outcome.program',
            // Basic
            'jenisKegiatan',
            'user.jabatan',
            // Collections
            'lokasi.desa.kecamatan.kabupaten.provinsi',
            'sektor',
            // Details
            'assessment', 'sosialisasi', 'pelatihan', 'pembelanjaan', 'pengembangan', 'kampanye',
            'pemetaan', 'monitoring', 'kunjungan', 'konsultasi', 'lainnya',
            // Writers
            'kegiatan_penulis.user.jabatan', 'kegiatan_penulis.peran',
        ])->findOrFail($id);

        // Alias: programActivity -> programOutcomeOutputActivity
        $act = $kegiatan->programOutcomeOutputActivity;
        if ($act) {
            $act->setRelation('output', $act->program_outcome_output);
            if ($act->relationLoaded('program_outcome_output') && $act->program_outcome_output) {
                $act->program_outcome_output->setRelation('outcome', $act->program_outcome_output->program_outcome);
            }
            $kegiatan->setRelation('programActivity', $act);
        }

        // Alias: *Detail -> existing relations
        $detailMap = [
            'assessmentDetail'     => 'assessment',
            'sosialisasiDetail'    => 'sosialisasi',
            'pelatihanDetail'      => 'pelatihan',
            'pembelanjaanDetail'   => 'pembelanjaan',
            'pengembanganDetail'   => 'pengembangan',
            'kampanyeDetail'       => 'kampanye',
            'pemetaanDetail'       => 'pemetaan',
            'monitoringDetail'     => 'monitoring',
            'kunjunganDetail'      => 'kunjungan',
            'konsultasiDetail'     => 'konsultasi',
            'lainnyaDetail'        => 'lainnya',
        ];
        foreach ($detailMap as $alias => $relation) {
            if ($kegiatan->relationLoaded($relation)) {
                $kegiatan->setRelation($alias, $kegiatan->getRelation($relation));
            } else {
                $kegiatan->setRelation($alias, $kegiatan->$relation()->first());
            }
        }

        // Alias: penulis -> kegiatan_penulis (so Blade can use $penulis->user, $penulis->peran)
        $kegiatan->setRelation('penulis', $kegiatan->kegiatan_penulis);

        // Fix: mitra collection to expose ->partner as in the Blade
        $mitraPivot = Kegiatan_Mitra::with('mitra')->where('kegiatan_id', $kegiatan->id)->get();
        foreach ($mitraPivot as $pivot) {
            if ($pivot->relationLoaded('mitra')) {
                $pivot->setRelation('partner', $pivot->getRelation('mitra'));
            }
        }
        $kegiatan->setRelation('mitra', $mitraPivot);

        // Alias each sektor as ->targetReinstra to match Blade usage
        $kegiatan->setRelation('sektor', $kegiatan->sektor->map(function ($s) {
            $s->setRelation('targetReinstra', $s);
            return $s;
        }));

        return view('tr.kegiatan.views.kegiatan', compact('kegiatan'));
    }

    /**
     * Handle Export BTOR button from the redesigned view.
     * Bridges POST form to existing export(docx) flow.
     */
    public function exportReport(Request $request)
    {
        $request->validate([
            'kegiatan_id' => ['required','integer'],
        ]);

        $kegiatan = Kegiatan::findOrFail((int) $request->input('kegiatan_id'));

        // Build DOCX like the main controller but sanitize HTML first
        $durationInDays = 0;
        if ($kegiatan->tanggalmulai && $kegiatan->tanggalselesai) {
            $durationInDays = Carbon::parse($kegiatan->tanggalmulai)
                ->diffInDays(Carbon::parse($kegiatan->tanggalselesai));
        }
        $data = compact('kegiatan', 'durationInDays');

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $html = view('tr.kegiatan.export', $data)->render();
        $sanitized = $this->sanitizeHtmlForWord($html);

        // Use fragment mode (fullHTML=false) to be more permissive
        Html::addHtml($section, $sanitized, false, false);

        $tempFile = tempnam(sys_get_temp_dir(), 'kegiatan');
        $tempFileDocx = $tempFile . '.docx';
        $phpWord->save($tempFileDocx, 'Word2007', true);

        return response()->download($tempFileDocx, 'kegiatan-' . $kegiatan->id . '.docx')
            ->deleteFileAfterSend(true);
    }

    private function sanitizeHtmlForWord(string $html): string
    {
        // Normalize self-closing tags for XML compatibility
        $replacements = [
            '/<br\s*>/i' => '<br/>',
            '/<hr\s*>/i' => '<hr/>',
            // Self-close img tags without ending slash
            '/<img([^>]*?)(?<!\/)>/i' => '<img$1 />',
        ];
        foreach ($replacements as $pattern => $replacement) {
            $html = preg_replace($pattern, $replacement, $html);
        }
        // Replace &nbsp; which can confuse some XML parsers
        $html = str_replace('&nbsp;', ' ', $html);
        return $html;
    }
}
