<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\PacienteResource\Pages\CreatePaciente;
use App\Filament\Resources\PacienteResource\Pages\EditPaciente;
use App\Filament\Resources\PacienteResource\Pages\ListPacientes;
use App\Models\Comuna;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * CRUD del recurso Paciente en Filament (panel admin).
 *
 * Requiere MySQL (o MariaDB) con las migraciones del proyecto: hay columnas SET
 * que no son compatibles con SQLite en memoria.
 */
class PacienteFilamentCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('regions')->insert([
            'id' => 1,
            'nombre' => 'Región QA (tests paciente)',
        ]);
        DB::table('comunas')->insert([
            'nombre' => 'Comuna QA',
            'region_id' => 1,
        ]);

        $this->admin = User::factory()->create();
    }

    public function test_list_pacientes_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListPacientes::class)
            ->assertSuccessful();
    }

    public function test_create_paciente_persists_row_and_motivo_consulta(): void
    {
        $this->actingAs($this->admin);

        $comuna = Comuna::query()->firstOrFail();

        $rut = '11111111-1';

        Livewire::test(CreatePaciente::class)
            ->fillForm($this->minimalPacienteFormPayload($comuna, $rut))
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pacientes', [
            'rut' => $rut,
            'nombre' => 'Paciente QA',
        ]);

        $this->assertDatabaseHas('motivo_consulta', [
            'paciente_rut' => $rut,
            'prioridad' => 1,
            'descripcion' => 'Control general',
        ]);
    }

    public function test_edit_paciente_updates_attributes(): void
    {
        $this->actingAs($this->admin);

        $comuna = Comuna::query()->firstOrFail();

        $rut = '22222222-2';

        Livewire::test(CreatePaciente::class)
            ->fillForm($this->minimalPacienteFormPayload($comuna, $rut))
            ->call('create')
            ->assertHasNoErrors();

        Livewire::test(EditPaciente::class, ['record' => $rut])
            ->fillForm([
                'nombre' => 'Paciente QA editado',
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pacientes', [
            'rut' => $rut,
            'nombre' => 'Paciente QA editado',
        ]);
    }

    public function test_delete_paciente_from_edit_page(): void
    {
        $this->actingAs($this->admin);

        $comuna = Comuna::query()->firstOrFail();
        $rut = '33333333-3';

        Livewire::test(CreatePaciente::class)
            ->fillForm($this->minimalPacienteFormPayload($comuna, $rut))
            ->call('create')
            ->assertHasNoErrors();

        $this->assertInstanceOf(Paciente::class, Paciente::query()->find($rut));

        Livewire::test(EditPaciente::class, ['record' => $rut])
            ->callAction('delete')
            ->assertHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseMissing('pacientes', ['rut' => $rut]);
    }

    /**
     * @return array<string, mixed>
     */
    private function minimalPacienteFormPayload(Comuna $comuna, string $rut): array
    {
        return [
            'nombre' => 'Paciente QA',
            'created_at' => now()->toDateString(),
            'ocupacion' => 'Tester',
            'fecha_nacimiento' => '1990-05-01',
            'rut' => $rut,
            'region_id' => 1,
            'comuna_id' => $comuna->id,
            'direccion_calle' => 'Calle Prueba',
            'direccion_numero' => 100,
            'direccion_complemento' => null,
            'telefono' => null,
            'celular' => 91234567,
            'correo' => 'qa_paciente@example.test',
            'contacto_nombre' => null,
            'contacto_telefono' => null,
            'contacto_celular' => null,
            'deporte' => 0,
            'alcohol' => 0,
            'fumar' => 0,
            'cafe' => 0,
            'agua' => 0,
            'motivo_consulta_1' => 'Control general',
            'motivo_consulta_2' => null,
            'motivo_consulta_3' => null,
        ];
    }
}
