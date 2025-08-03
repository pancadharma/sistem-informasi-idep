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

            // Extract the original filename from the temp file path
            $tempFilename = pathinfo($filePath, PATHINFO_FILENAME);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            
            // Try to extract the original filename from the temp filename
            // Temp files are stored as uniqid() . '_' . trim($file->getClientOriginalName())
            $parts = explode('_', $tempFilename, 2);
            $originalDisplayName = isset($parts[1]) ? $parts[1] : $tempFilename;
            
            // Get the file extension from the original display name if it has one
            $originalName = pathinfo($originalDisplayName, PATHINFO_FILENAME);
            $originalExtension = pathinfo($originalDisplayName, PATHINFO_EXTENSION);
            
            // Use the original extension if available, otherwise use the temp file extension
            $fileExtension = $originalExtension ?: $extension;
            
            $kegiatanName = str_replace(' ', '_', $this->kegiatan->nama ?? 'kegiatan');
            $fileName = "{$kegiatanName}_{$timestamp}_{$fileCount}.{$fileExtension}";
            
            // Use the caption if provided, otherwise use the original display name with extension
            $keterangan = $this->captions[$index] ?? ($originalDisplayName . ($fileExtension ? ".{$fileExtension}" : ''));
            
            $collectionName = $this->collectionNames[$index] ?? 'media_pendukung';

            try {
                $this->kegiatan
                    ->addMedia($filePath)
                    ->withCustomProperties([
                        'keterangan' => $keterangan,
                        'user_id' => $this->kegiatan->user_id,
                        'original_name' => $originalName,
                        'extension' => $fileExtension,
                        'updated_by' => $this->kegiatan->user_id
                    ])
                    ->usingName($originalDisplayName)
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