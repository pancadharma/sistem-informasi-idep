<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Program;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;

class ProgramObjectiveTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_program_successfully()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/programs', [
            'nama'                                  => $this->faker->name,
            'user_id'                               => $user->id,
            'kode'                                  => $this->faker->word,
            'tanggalmulai'                          => '2024-01-01',
            'tanggalselesai'                        => '2024-12-31',
            'totalnilai'                            => 10000,
            'ekspektasipenerimamanfaat'             => 100,
            'ekspektasipenerimamanfaatwoman'        => 50,
            'ekspektasipenerimamanfaatman'          => 50,
            'ekspektasipenerimamanfaatgirl'         => 25,
            'ekspektasipenerimamanfaatboy'          => 25,
            'ekspektasipenerimamanfaattidaklangsung'=> 100,
            'deskripsiprojek'                       => $this->faker->text(500),
            'analisamasalah'                        => $this->faker->text(500),
            'status'                                => $this->faker->word,
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Program created successfully!']);

        $this->assertDatabaseHas('programs', [
            'nama' => $response['nama'],
        ]);
    }
}
