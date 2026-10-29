<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);
    }

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

    public function test_project_sample_seeder_creates_twenty_five_records(): void
    {
        $this->seed(\Database\Seeders\ProjectSampleSeeder::class);

        $this->assertSame(25, Project::count());
        $this->assertSame(25, Project::published()->count());
        $this->assertSame(25, Project::distinct('title')->count('title'));
    }

    public function test_database_seeder_calls_project_sample_seeder(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $this->assertSame(25, Project::count());
    }

    public function test_pagination_defaults_to_ten_items_per_page(): void
    {
        $this->seed(\Database\Seeders\ProjectSampleSeeder::class);

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertSee('Menampilkan <strong>1</strong> &ndash; <strong>10</strong> dari <strong>25</strong> project', false);
        $response->assertSee('#1');
        $response->assertSee('#10');
        $response->assertDontSee('#11');
    }

    public function test_pagination_respects_per_page_options(): void
    {
        $this->seed(\Database\Seeders\ProjectSampleSeeder::class);

        $responseFive = $this->get(route('projects.index', ['per_page' => 5]));
        $responseFive->assertStatus(200);
        $responseFive->assertSee('Menampilkan <strong>1</strong> &ndash; <strong>5</strong> dari <strong>25</strong> project', false);

        $responseTwentyFive = $this->get(route('projects.index', ['per_page' => 25]));
        $responseTwentyFive->assertStatus(200);
        $responseTwentyFive->assertSee('Menampilkan <strong>1</strong> &ndash; <strong>25</strong> dari <strong>25</strong> project', false);

        $responseInvalid = $this->get(route('projects.index', ['per_page' => 999]));
        $responseInvalid->assertStatus(200);
        $responseInvalid->assertSee('Menampilkan <strong>1</strong> &ndash; <strong>10</strong> dari <strong>25</strong> project', false);
    }

    public function test_running_number_continues_across_pages(): void
    {
        $this->seed(\Database\Seeders\ProjectSampleSeeder::class);

        $pageTwo = $this->get(route('projects.index', ['page' => 2, 'per_page' => 10]));
        $pageTwo->assertStatus(200);
        $pageTwo->assertSee('Menampilkan <strong>11</strong> &ndash; <strong>20</strong> dari <strong>25</strong> project', false);
        $pageTwo->assertSee('#11');
        $pageTwo->assertSee('#20');

        $pageThree = $this->get(route('projects.index', ['page' => 3, 'per_page' => 10]));
        $pageThree->assertStatus(200);
        $pageThree->assertSee('Menampilkan <strong>21</strong> &ndash; <strong>25</strong> dari <strong>25</strong> project', false);
        $pageThree->assertSee('#21');
        $pageThree->assertSee('#25');
    }

    public function test_search_filters_projects_by_title_and_maintains_query_string(): void
    {
        $this->seed(\Database\Seeders\ProjectSampleSeeder::class);

        $response = $this->get(route('projects.index', ['search' => 'Akademik']));
        $response->assertStatus(200);
        $response->assertSee('Sistem Informasi Akademik Kampus');
        $response->assertDontSee('E-Commerce UMKM Batik Tradisional');

        $emptyResponse = $this->get(route('projects.index', ['search' => 'KeywordTidakDitemukan']));
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Tidak ada project yang sesuai dengan kata kunci "<strong>KeywordTidakDitemukan</strong>".', false);
        $emptyResponse->assertSee('Reset Pencarian');
    }
}