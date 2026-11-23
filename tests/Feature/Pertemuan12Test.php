<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class Pertemuan12Test extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('portfolio-api|127.0.0.1');
        RateLimiter::clear('contact|127.0.0.1');

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
    }

    public function test_api_projects_returns_consistent_json_and_rate_limit_headers(): void
    {
        $category = Category::create(['name' => 'Web Development', 'slug' => 'web-development']);

        Project::create([
            'title' => 'Sistem Portal Akademik',
            'description' => 'Aplikasi web akademik untuk mahasiswa.',
            'status' => 'published',
            'technology' => 'Laravel',
            'category_id' => $category->id,
        ]);

        Project::create([
            'title' => 'Project Draft Internal',
            'description' => 'Tidak boleh muncul di API publik.',
            'status' => 'draft',
            'technology' => 'Laravel',
            'category_id' => $category->id,
        ]);

        $response = $this->getJson('/api/projects');

        $response->assertOk()
            ->assertHeader('X-RateLimit-Limit', 60)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'title', 'slug', 'description', 'technology', 'project_date', 'thumbnail', 'category', 'category_slug'],
                ],
                'links',
                'meta' => ['current_page', 'per_page', 'total'],
            ])
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Sistem Portal Akademik')
            ->assertJsonPath('data.0.category', 'Web Development');
    }

    public function test_api_projects_supports_search_category_combined_and_empty_results(): void
    {
        $web = Category::create(['name' => 'Web Development', 'slug' => 'web-development']);
        $mobile = Category::create(['name' => 'Mobile App', 'slug' => 'mobile-app']);

        Project::create([
            'title' => 'Aplikasi Kasir Web',
            'description' => 'Sistem kasir berbasis browser.',
            'status' => 'published',
            'category_id' => $web->id,
        ]);

        Project::create([
            'title' => 'Aplikasi Kasir Android',
            'description' => 'Sistem kasir untuk perangkat mobile.',
            'status' => 'published',
            'category_id' => $mobile->id,
        ]);

        Project::create([
            'title' => 'Portal Berita Kampus',
            'description' => 'Portal artikel mahasiswa.',
            'status' => 'published',
            'category_id' => $web->id,
        ]);

        $searchRes = $this->getJson('/api/projects?search=Kasir');
        $searchRes->assertOk()->assertJsonPath('meta.total', 2);

        $categoryRes = $this->getJson('/api/projects?category=mobile-app');
        $categoryRes->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Aplikasi Kasir Android');

        $combinedRes = $this->getJson('/api/projects?search=Kasir&category=web-development');
        $combinedRes->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Aplikasi Kasir Web');

        $emptyRes = $this->getJson('/api/projects?search=TidakAdaSamaSekali');
        $emptyRes->assertOk()
            ->assertJsonPath('data', [])
            ->assertJsonPath('meta.total', 0)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_api_projects_pagination_preserves_query_string(): void
    {
        $web = Category::create(['name' => 'Web Development', 'slug' => 'web-development']);

        for ($i = 1; $i <= 12; $i++) {
            Project::create([
                'title' => "Proyek Filter Ke {$i}",
                'description' => 'Deskripsi proyek uji pagination API.',
                'status' => 'published',
                'category_id' => $web->id,
            ]);
        }

        $response = $this->getJson('/api/projects?search=Filter&category=web-development');

        $response->assertOk()
            ->assertJsonPath('meta.total', 12)
            ->assertJsonCount(9, 'data');

        $nextUrl = $response->json('links.next');
        $this->assertStringContainsString('search=Filter', $nextUrl);
        $this->assertStringContainsString('category=web-development', $nextUrl);
    }

    public function test_api_project_show_by_slug_and_json_404(): void
    {
        $project = Project::create([
            'title' => 'Project Detail API',
            'slug' => 'project-detail-api',
            'description' => 'Deskripsi lengkap project detail API.',
            'status' => 'published',
        ]);

        $this->getJson('/api/projects/' . $project->slug)
            ->assertOk()
            ->assertJsonPath('data.slug', 'project-detail-api');

        $this->get('/api/projects/slug-tidak-ada')
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json');
    }

    public function test_api_categories_returns_with_count_and_rate_limit_header(): void
    {
        $web = Category::create(['name' => 'Web Development', 'slug' => 'web-development']);
        $iot = Category::create(['name' => 'IoT & Automation', 'slug' => 'iot-automation']);

        Project::create([
            'title' => 'Web Satu',
            'description' => 'Deskripsi web satu.',
            'status' => 'published',
            'category_id' => $web->id,
        ]);

        Project::create([
            'title' => 'Web Dua',
            'description' => 'Deskripsi web dua.',
            'status' => 'published',
            'category_id' => $web->id,
        ]);

        Project::create([
            'title' => 'Sensor IoT',
            'description' => 'Deskripsi sensor IoT.',
            'status' => 'published',
            'category_id' => $iot->id,
        ]);

        $response = $this->getJson('/api/categories');

        $response->assertOk()
            ->assertHeader('X-RateLimit-Limit', 60)
            ->assertJsonFragment([
                'id' => $web->id,
                'name' => 'Web Development',
                'slug' => 'web-development',
                'projects_count' => 2,
            ])
            ->assertJsonFragment([
                'id' => $iot->id,
                'name' => 'IoT & Automation',
                'slug' => 'iot-automation',
                'projects_count' => 1,
            ]);
    }

    public function test_api_rate_limiting_returns_429_on_61st_request(): void
    {
        $ip = '10.0.0.50';

        for ($i = 1; $i <= 60; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => $ip])
                ->getJson('/api/projects')
                ->assertOk();
        }

        $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->getJson('/api/projects')
            ->assertStatus(429)
            ->assertHeader('Retry-After');
    }

    public function test_contact_form_validation_storage_and_rate_limiting(): void
    {
        $this->get('/contact')->assertOk()->assertSee('Hubungi Saya');

        $ip = '10.0.0.60';

        $invalid = $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->post('/contact', [
                'name' => '',
                'email' => 'bukan-email',
                'message' => 'pendek',
            ]);

        $invalid->assertSessionHasErrors(['name', 'email', 'message']);

        // Reset counter after validation attempt before testing 3 valid submissions + 4th 429
        $ipValid = '10.0.0.61';

        for ($i = 1; $i <= 3; $i++) {
            $res = $this->withServerVariables(['REMOTE_ADDR' => $ipValid])
                ->post('/contact', [
                    'name' => "Pengirim {$i}",
                    'email' => "pengirim{$i}@example.com",
                    'subject' => "Subjek Pesan {$i}",
                    'message' => "Ini adalah pesan uji coba ke-{$i} yang valid.",
                ]);

            $res->assertRedirect();
            $res->assertSessionHas('success');
        }

        $this->assertDatabaseCount('contacts', 3);
        $this->assertSame(3, Contact::where('is_read', false)->count());

        $fourth = $this->withServerVariables(['REMOTE_ADDR' => $ipValid])
            ->post('/contact', [
                'name' => 'Pengirim 4',
                'email' => 'pengirim4@example.com',
                'subject' => 'Spam',
                'message' => 'Pesan keempat dalam satu menit harus ditolak.',
            ]);

        $fourth->assertStatus(429);
    }

    public function test_admin_contacts_auth_listing_pagination_and_mark_as_read(): void
    {
        $this->get('/post/contacts')->assertRedirect('/login');
        $this->actingAs($this->regularUser)->get('/post/contacts')->assertForbidden();

        for ($i = 1; $i <= 12; $i++) {
            Contact::create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'subject' => "Subjek {$i}",
                'message' => "Isi pesan pengujian nomor {$i} untuk pagination.",
                'is_read' => false,
            ]);
        }

        $latestContact = Contact::latest('id')->first();

        $indexRes = $this->actingAs($this->admin)->get('/post/contacts');
        $indexRes->assertOk()
            ->assertSee($latestContact->name)
            ->assertSee('Belum dibaca');

        $showRes = $this->actingAs($this->admin)->get(route('post.contacts.show', $latestContact));
        $showRes->assertOk()
            ->assertSee($latestContact->message)
            ->assertSee('Sudah dibaca');

        $latestContact->refresh();
        $this->assertTrue($latestContact->is_read);
    }

    public function test_dashboard_displays_accurate_message_and_unread_counts(): void
    {
        $this->get('/post/dashboard')->assertRedirect('/login');
        $this->actingAs($this->regularUser)->get('/post/dashboard')->assertForbidden();

        $contacts = [];
        for ($i = 1; $i <= 3; $i++) {
            $contacts[] = Contact::create([
                'name' => "Tamu {$i}",
                'email' => "tamu{$i}@example.com",
                'subject' => "Pertanyaan {$i}",
                'message' => "Halo, ini pesan pengujian dashboard nomor {$i}.",
                'is_read' => false,
            ]);
        }

        $beforeRead = $this->actingAs($this->admin)->get('/post/dashboard');
        $beforeRead->assertOk()
            ->assertSee('Total Projects')
            ->assertSee('Messages')
            ->assertSee('Unread Messages')
            ->assertViewHas('totalMessages', 3)
            ->assertViewHas('unreadMessages', 3);

        $this->actingAs($this->admin)->get(route('post.contacts.show', $contacts[0]))->assertOk();

        $afterRead = $this->actingAs($this->admin)->get('/post/dashboard');
        $afterRead->assertOk()
            ->assertViewHas('totalMessages', 3)
            ->assertViewHas('unreadMessages', 2);
    }
}
