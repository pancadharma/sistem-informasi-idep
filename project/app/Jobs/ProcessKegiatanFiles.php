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
    protected $collectionNames;
    /**
     * Create a new job instance.
     */
    public function __construct(Kegiatan $kegiatan, array $filePaths, array $captions, array $collectionNames)
    {
        $this->kegiatan = $kegiatan;
        $this->filePaths = $filePaths;
        $this->captions = $captions;
        $this->collectionNames = $collectionNames;
    }

    /**
     * Execute the job.
     */
        public function handle()
    {
        $timestamp = now()->format('Ymd_His');
        $fileCount = 1;

        foreach ($this->filePaths as $index => $filePath) {
            if (!file_exists($filePath)) {
                continue;
            }

            $originalName = pathinfo($filePath, PATHINFO_FILENAME);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $kegiatanName = str_replace(' ', '_', $this->kegiatan->nama ?? 'kegiatan');
            $fileName = "{$kegiatanName}_{$timestamp}_{$fileCount}.{$extension}";
            $keterangan = $this->captions[$index] ?? $fileName;
            $collectionName = $this->collectionNames[$index] ?? 'media_pendukung';

            try {
                $this->kegiatan
                    ->addMedia($filePath)
                    ->withCustomProperties([
                        'keterangan' => $keterangan,
                        'user_id' => $this->kegiatan->user_id,
                        'original_name' => $originalName,
                        'extension' => $extension
                    ])
                    ->usingName("{$kegiatanName}_{$originalName}_{$fileCount}")
                    ->usingFileName($fileName)
                    ->toMediaCollection($collectionName);

                // Clean up temporary file
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            } catch (\Exception $e) {
                // Log error but continue processing other files
                \Log::error('Failed to process media file: ' . $e->getMessage(), [
                    'file_path' => $filePath,
                    'kegiatan_id' => $this->kegiatan->id,
                    'collection' => $collectionName
                ]);
            }

            $fileCount++;
        }
    }
}