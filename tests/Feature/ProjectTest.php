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
            'status' => 'published',
        ];

        $response = $this->post(route('projects.store'), $payload);

        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'title' => 'Test Automation Project',
            'status' => 'published',
        ]);
    }

    public function test_projects_index_only_displays_published_projects(): void
    {
        $published = Project::create([
            'title' => 'Published Project',
            'description' => 'Deskripsi project published.',
            'status' => 'published',
        ]);

        $draft = Project::create([
            'title' => 'Draft Project',
            'description' => 'Deskripsi project draft.',
            'status' => 'draft',
        ]);

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertSee('Published Project');
        $response->assertDontSee('Draft Project');
    }

    public function test_scope_published_filters_correctly(): void
    {
        Project::create([
            'title' => 'Published Item',
            'description' => 'Deskripsi project published.',
            'status' => 'published',
        ]);

        Project::create([
            'title' => 'Draft Item',
            'description' => 'Deskripsi project draft.',
            'status' => 'draft',
        ]);

        $publishedCount = Project::published()->count();
        $this->assertEquals(1, $publishedCount);
    }

    public function test_soft_delete_moves_project_to_trash(): void
    {
        $project = Project::create([
            'title' => 'Soft Deleted Project',
            'description' => 'Deskripsi project yang akan dihapus.',
            'status' => 'published',
        ]);

        $response = $this->delete(route('projects.destroy', $project));
        $response->assertRedirect(route('projects.index'));

        $this->assertSoftDeleted('projects', ['id' => $project->id]);

        $indexResponse = $this->get(route('projects.index'));
        $indexResponse->assertDontSee('Soft Deleted Project');

        $trashResponse = $this->get(route('projects.trash'));
        $trashResponse->assertStatus(200);
        $trashResponse->assertSee('Soft Deleted Project');
    }

    public function test_project_can_be_restored(): void
    {
        $project = Project::create([
            'title' => 'Restorable Project',
            'description' => 'Deskripsi project yang akan di-restore.',
            'status' => 'published',
        ]);
        $project->delete();

        $response = $this->patch(route('projects.restore', $project->id));
        $response->assertRedirect(route('projects.trash'));

        $this->assertNotSoftDeleted('projects', ['id' => $project->id]);

        $indexResponse = $this->get(route('projects.index'));
        $indexResponse->assertSee('Restorable Project');
    }

    public function test_draft_project_remains_hidden_from_index_after_restore(): void
    {
        $project = Project::create([
            'title' => 'Restorable Draft Project',
            'description' => 'Deskripsi draft project yang di-restore.',
            'status' => 'draft',
        ]);
        $project->delete();

        $response = $this->patch(route('projects.restore', $project->id));
        $response->assertRedirect(route('projects.trash'));

        $this->assertNotSoftDeleted('projects', ['id' => $project->id]);

        $indexResponse = $this->get(route('projects.index'));
        $indexResponse->assertDontSee('Restorable Draft Project');
    }

    public function test_project_can_be_force_deleted(): void
    {
        $project = Project::create([
            'title' => 'Permanent Delete Project',
            'description' => 'Deskripsi project yang akan dihapus permanen.',
            'status' => 'published',
        ]);
        $project->delete();

        $response = $this->delete(route('projects.forceDelete', $project->id));
        $response->assertRedirect(route('projects.trash'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_project_detail_page_is_successful(): void
    {
        $project = Project::create([
            'title' => 'Test Detail Project',
            'description' => 'Deskripsi untuk pengujian halaman detail.',
            'status' => 'published',
        ]);

        $response = $this->get(route('projects.show', $project));

        $response->assertStatus(200);
        $response->assertSee($project->title);
        $response->assertSee('Kembali ke Projects');
        $response->assertSee('nav-link active', false);
    }

    public function test_static_pages_regression(): void
    {
        $this->get(route('home'))->assertStatus(200);
        $this->get(route('about'))->assertStatus(200);
        $this->get(route('education'))->assertStatus(200);
    }
}