<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Program;
use App\Models\Partner;
use App\Models\Peran;
use App\Jobs\ProcessProgramFiles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class ProgramCreationRefactorTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user
        // Assuming you have a user factory and the user needs certain permissions to create a program.
        // For simplicity, we'll just create a user. You might need to assign roles/permissions
        // depending on your application's authorization setup (e.g., using Spatie's permissions package).
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Test that a program can be created successfully via the web route,
     * and it dispatches the file processing job.
     *
     * @return void
     */
    public function test_program_can_be_created_and_file_job_is_dispatched()
    {
        // 1. Arrange
        // ===================================================================

        // Fake the Queue and Storage to prevent real jobs and file operations
        Queue::fake();
        Storage::fake('temp_program_files');

        // Create necessary related data
        $partner = Partner::factory()->create();
        $staffUser = User::factory()->create();
        $peran = Peran::create(['nama' => 'Project Manager', 'aktif' => 1]); // Assuming Peran model is simple

        // Prepare the request payload
        $programData = [
            'nama' => $this->faker->sentence(3),
            'kode' => $this->faker->unique()->word,
            'tanggalmulai' => now()->toDateString(),
            'tanggalselesai' => now()->addYear()->toDateString(),
            'status' => 'Planned',
            'deskripsi' => $this->faker->paragraph,
            // --- Relationships ---
            'partner' => [$partner->id],
            'staff' => [$staffUser->id],
            'peran' => [$peran->id],
            // Add other necessary fields as per your StoreProgramRequest validation rules
            // For example: 'targetreinstra', 'kelompokmarjinal', 'kaitansdg', 'lokasi', etc.
            // 'targetreinstra' => [TargetReinstra::factory()->create()->id],
        ];

        // Prepare the file for upload
        $fileData = [
            'file_pendukung' => [
                UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf')
            ],
            'captions' => [
                'Test Document Caption'
            ]
        ];

        // 2. Act
        // ===================================================================

        // Call the store endpoint
        // Note: Ensure the route name 'admin.program.store' is correct. Check your routes/web.php.
        $response = $this->post(route('admin.program.store'), array_merge($programData, $fileData));


        // 3. Assert
        // ===================================================================

        // Assert the response is successful and has the correct structure
        $response->assertStatus(201) // HTTP 201 Created
                 ->assertJson([
                     'success' => true,
                     'message' => fn ($message) => str_contains($message, 'File akan diproses di latar belakang'),
                 ]);

        // Assert the program was created in the database
        $this->assertDatabaseHas('trprogram', [
            'nama' => $programData['nama'],
            'kode' => $programData['kode'],
        ]);

        // Get the created program to check relationships
        $createdProgram = Program::where('kode', $programData['kode'])->first();
        $this->assertNotNull($createdProgram);

        // Assert relationships were synced correctly
        $this->assertDatabaseHas('trprogrampartner', [
            'program_id' => $createdProgram->id,
            'partner_id' => $partner->id,
        ]);

        $this->assertDatabaseHas('trprogramuser', [
            'program_id' => $createdProgram->id,
            'user_id' => $staffUser->id,
            'peran_id' => $peran->id,
        ]);

        // Assert that the job was pushed to the queue
        Queue::assertPushed(ProcessProgramFiles::class, function ($job) use ($createdProgram) {
            // Verify that the job was dispatched with the correct program
            return $job->program->id === $createdProgram->id;
        });
    }

    /**
     * Test program creation fails without a required field.
     *
     * @return void
     */
    public function test_program_creation_fails_on_validation_error()
    {
        // 1. Arrange
        $invalidData = [
            'kode' => 'TESTCODE', // Missing 'nama' which is likely required
        ];

        // 2. Act
        $response = $this->postJson(route('admin.program.store'), $invalidData);

        // 3. Assert
        $response->assertStatus(422) // HTTP 422 Unprocessable Entity
                 ->assertJsonValidationErrors('nama');
    }
}
