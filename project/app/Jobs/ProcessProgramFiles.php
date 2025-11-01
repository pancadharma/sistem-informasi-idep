<?php

namespace App\Jobs;

use App\Models\Program;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProgramFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $program;
    protected $filePaths;
    protected $captions;

    /**
     * Create a new job instance.
     */
    public function __construct(Program $program, array $filePaths, array $captions)
    {
        $this->program = $program;
        $this->filePaths = $filePaths;
        $this->captions = $captions;
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
            $programName = str_replace(' ', '_', $this->program->nama ?? 'program');
            $fileName = "{$programName}_{$timestamp}_{$fileCount}.{$extension}";
            $keterangan = $this->captions[$index] ?? $fileName;

            try {
                $this->program
                    ->addMedia($filePath)
                    ->withCustomProperties([
                        'keterangan' => $keterangan,
                        'user_id' => $this->program->user_id,
                        'original_name' => $originalName,
                        'extension' => $extension
                    ])
                    ->usingName("{$programName}_{$originalName}_{$fileCount}")
                    ->usingFileName($fileName)
                    ->toMediaCollection('program_' . $this->program->id, 'program_uploads');

                // Clean up temporary file
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            } catch (\Exception $e) {
                // Log error but continue processing other files
                \Log::error('Failed to process media file: ' . $e->getMessage(), [
                    'file_path' => $filePath,
                    'program_id' => $this->program->id,
                    'collection' => 'program_' . $this->program->id
                ]);
            }

            $fileCount++;
        }
    }
}