<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_index_page_is_successful_and_displays_projects(): void
    {
        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertSee('Projects');
        $response->assertSee('Tambah Project');
        $response->assertSee('nav-link active', false);
    }

    public function test_projects_create_page_is_successful_and_renders_form(): void
    {
        $response = $this->get(route('projects.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Project');
        $response->assertSee('Judul Project');
        $response->assertSee('Deskripsi Project');
        $response->assertSee('Simpan Project');
        $response->assertSee('Kembali ke Projects');
        $response->assertSee('nav-link active', false);
    }

    public function test_project_can_be_stored_and_redirects(): void
    {
        $payload = [
            'title' => 'Test Automation Project',
            'description' => 'Deskripsi untuk pengujian otomatis.',
        ];

        $response = $this->post(route('projects.store'), $payload);

        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'title' => 'Test Automation Project',
        ]);

        // Clean up created test record so database remains pristine
        Project::where('title', 'Test Automation Project')->delete();
    }

    public function test_project_detail_page_is_successful(): void
    {
        $project = new Project();
        $project->title = 'Test Detail Project';
        $project->description = 'Deskripsi untuk pengujian halaman detail.';
        $project->save();

        $response = $this->get(route('projects.show', $project));

        $response->assertStatus(200);
        $response->assertSee($project->title);
        $response->assertSee('Kembali ke Projects');
        $response->assertSee('nav-link active', false);
    }
}