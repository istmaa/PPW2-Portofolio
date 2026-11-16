<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Pertemuan11Test extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->regularUser = User::factory()->create([
            'role' => 'user',
        ]);
    }

    public function test_filter_technology_displays_only_matching_projects(): void
    {
        Project::create([
            'title' => 'Laravel Inventory System',
            'description' => 'Aplikasi inventaris berbasis Laravel.',
            'status' => 'published',
            'technology' => 'Laravel',
            'project_date' => '2026-02-01',
        ]);

        Project::create([
            'title' => 'React Dashboard App',
            'description' => 'Frontend dashboard menggunakan React.',
            'status' => 'published',
            'technology' => 'React',
            'project_date' => '2026-03-01',
        ]);

        $response = $this->get(route('projects.index', ['technology' => 'Laravel']));

        $response->assertOk();
        $response->assertSee('Laravel Inventory System');
        $response->assertDontSee('React Dashboard App');
        $response->assertSee('value="Laravel" selected', false);
        $response->assertSee('1</strong> hasil ditemukan', false);
    }

    public function test_sorting_by_project_date_newest_and_oldest(): void
    {
        $oldProject = Project::create([
            'title' => 'Proyek Terlama 2024',
            'description' => 'Proyek lama dikerjakan awal tahun.',
            'status' => 'published',
            'technology' => 'Flutter',
            'project_date' => '2024-01-15',
        ]);

        $newProject = Project::create([
            'title' => 'Proyek Terbaru 2026',
            'description' => 'Proyek baru dikerjakan akhir tahun.',
            'status' => 'published',
            'technology' => 'Vue.js',
            'project_date' => '2026-11-01',
        ]);

        $responseNewest = $this->get(route('projects.index', ['sort' => 'newest']));
        $responseNewest->assertOk();
        $responseNewest->assertSeeInOrder(['Proyek Terbaru 2026', 'Proyek Terlama 2024']);
        $responseNewest->assertSee('value="newest" selected', false);

        $responseOldest = $this->get(route('projects.index', ['sort' => 'oldest']));
        $responseOldest->assertOk();
        $responseOldest->assertSeeInOrder(['Proyek Terlama 2024', 'Proyek Terbaru 2026']);
        $responseOldest->assertSee('value="oldest" selected', false);
    }

    public function test_combined_search_technology_and_sorting(): void
    {
        Project::create([
            'title' => 'Sistem Kasir Pintar Laravel',
            'description' => 'Point of sale modern.',
            'status' => 'published',
            'technology' => 'Laravel',
            'project_date' => '2026-01-10',
        ]);

        Project::create([
            'title' => 'Sistem Perpustakaan Laravel',
            'description' => 'Katalog buku perpustakaan.',
            'status' => 'published',
            'technology' => 'Laravel',
            'project_date' => '2026-05-15',
        ]);

        Project::create([
            'title' => 'Sistem Kasir React',
            'description' => 'Point of sale berbasis React.',
            'status' => 'published',
            'technology' => 'React',
            'project_date' => '2026-02-10',
        ]);

        $response = $this->get(route('projects.index', [
            'search' => 'Kasir',
            'technology' => 'Laravel',
            'sort' => 'newest',
        ]));

        $response->assertOk();
        $response->assertSee('Sistem Kasir Pintar Laravel');
        $response->assertDontSee('Sistem Perpustakaan Laravel');
        $response->assertDontSee('Sistem Kasir React');
        $response->assertSee('1</strong> hasil ditemukan', false);
        $response->assertSee('Reset Filter');
    }

    public function test_empty_search_shows_zero_results_and_reset_button(): void
    {
        Project::create([
            'title' => 'Proyek Aktif',
            'description' => 'Deskripsi proyek aktif.',
            'status' => 'published',
            'technology' => 'Laravel',
            'project_date' => '2026-01-01',
        ]);

        $response = $this->get(route('projects.index', ['search' => 'KeywordTidakAdaSamaSekali']));

        $response->assertOk();
        $response->assertSee('0 hasil ditemukan');
        $response->assertSee('Reset Pencarian');
        $response->assertSee(route('projects.index'));
    }

    public function test_pagination_maintains_query_strings(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Project::create([
                'title' => "Proyek Edukasi ke-{$i}",
                'description' => 'Deskripsi untuk pengujian pagination dan query string.',
                'status' => 'published',
                'technology' => 'Python',
                'project_date' => date('Y-m-d', strtotime("2026-01-01 + {$i} days")),
            ]);
        }

        $response = $this->get(route('projects.index', [
            'technology' => 'Python',
            'sort' => 'newest',
            'per_page' => 5,
            'page' => 2,
        ]));

        $response->assertOk();
        $response->assertSee('technology=Python');
        $response->assertSee('sort=newest');
        $response->assertSee('per_page=5');
    }

    public function test_admin_projects_page_requires_auth_and_admin_middleware(): void
    {
        $guestResponse = $this->get(route('post.projects'));
        $guestResponse->assertRedirect(route('login'));

        $userResponse = $this->actingAs($this->regularUser)->get(route('post.projects'));
        $userResponse->assertForbidden();

        $adminResponse = $this->actingAs($this->admin)->get(route('post.projects'));
        $adminResponse->assertOk();
        $adminResponse->assertSee('Pengelolaan Project');
    }

    public function test_admin_projects_search_and_filter_status(): void
    {
        Project::create([
            'title' => 'Aplikasi Audit Finansial Draft',
            'description' => 'Masih dalam pengembangan internal.',
            'status' => 'draft',
            'technology' => 'Laravel',
            'project_date' => '2026-04-01',
        ]);

        Project::create([
            'title' => 'Aplikasi Audit Medis Published',
            'description' => 'Sudah diluncurkan ke pengguna.',
            'status' => 'published',
            'technology' => 'Laravel',
            'project_date' => '2026-04-10',
        ]);

        $draftResponse = $this->actingAs($this->admin)->get(route('post.projects', [
            'search' => 'Audit',
            'status' => 'draft',
        ]));

        $draftResponse->assertOk();
        $draftResponse->assertSee('Aplikasi Audit Finansial Draft');
        $draftResponse->assertDontSee('Aplikasi Audit Medis Published');
        $draftResponse->assertSee('value="draft" selected', false);

        $publishedResponse = $this->actingAs($this->admin)->get(route('post.projects', [
            'search' => 'Audit',
            'status' => 'published',
        ]));

        $publishedResponse->assertOk();
        $publishedResponse->assertSee('Aplikasi Audit Medis Published');
        $publishedResponse->assertDontSee('Aplikasi Audit Finansial Draft');
        $publishedResponse->assertSee('value="published" selected', false);
    }

    public function test_query_optimization_avoids_n_plus_one_queries(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $project = Project::create([
                'title' => "Proyek Galeri ke-{$i}",
                'description' => 'Deskripsi proyek dengan relasi gambar.',
                'status' => 'published',
                'technology' => 'Laravel',
                'project_date' => '2026-06-01',
            ]);

            ProjectImage::create([
                'project_id' => $project->id,
                'image' => "projects/gallery/sample_{$i}.jpg",
                'sort_order' => 1,
            ]);
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->get(route('projects.index'));
        $response->assertOk();

        $queries = DB::getQueryLog();

        // 1: count query for pagination
        // 2: distinct technologies query
        // 3: select projects query
        // 4: eager load project_images query (where in project_id)
        // Ensure query count stays constant (<= 5 queries) and does NOT grow with N items
        $this->assertLessThanOrEqual(5, count($queries), 'Query count exceeds expected limit, possible N+1 issue.');
    }
}
