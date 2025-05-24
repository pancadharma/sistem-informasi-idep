<?php

namespace App\Jobs;

use App\Models\Kegiatan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessKegiatanFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $kegiatan;
    protected $filePaths;
    protected $captions;
    protected $collectionName;
    /**
     * Create a new job instance.
     */
    public function __construct(Kegiatan $kegiatan, array $filePaths, array $captions, string $collectionName)
    {
        $this->kegiatan = $kegiatan;
        $this->filePaths = $filePaths;
        $this->captions = $captions;
        $this->collectionName = $collectionName;
    }

    /**
     * Execute the job.
     */
        public function handle()
    {
        $timestamp = now()->format('Ymd_His');
        $fileCount = 1;

        foreach ($this->filePaths as $index => $filePath) {
            $originalName = pathinfo($filePath, PATHINFO_FILENAME);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $kegiatanName = str_replace(' ', '_', $this->kegiatan->nama ?? 'kegiatan');
            $fileName = "{$kegiatanName}_{$timestamp}_{$fileCount}.{$extension}";
            $keterangan = $this->captions[$index] ?? $fileName;

            $this->kegiatan
                ->addMedia($filePath)
                ->withCustomProperties([
                    'keterangan' => $keterangan,
                    'user_id' => auth()->user()->id,
                    'original_name' => $originalName,
                    'extension' => $extension
                ])
                ->usingName("{$kegiatanName}_{$originalName}_{$fileCount}")
                ->usingFileName($fileName)
                ->toMediaCollection($this->collectionName);

            $fileCount++;
        }
    }
}