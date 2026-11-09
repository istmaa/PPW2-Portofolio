<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Pertemuan10Test extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->regularUser = User::factory()->create([
            'role' => 'user',
        ]);
    }

    public function test_admin_can_upload_thumbnail_jpg_and_displays_on_public_page(): void
    {
        $thumbnail = UploadedFile::fake()->image('cover.jpg', 640, 480);

        $response = $this->actingAs($this->admin)->post(route('projects.store'), [
            'title' => 'Web Portfolio Project',
            'description' => 'Deskripsi project yang diupload thumbnailnya.',
            'status' => 'published',
            'thumbnail' => $thumbnail,
        ]);

        $response->assertRedirect(route('projects.index'));

        $project = Project::where('title', 'Web Portfolio Project')->firstOrFail();
        $this->assertNotNull($project->thumbnail);

        Storage::disk('public')->assertExists($project->thumbnail);

        $indexResponse = $this->get(route('projects.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee(asset('storage/' . $project->thumbnail));

        $showResponse = $this->get(route('projects.show', $project));
        $showResponse->assertOk();
        $showResponse->assertSee(asset('storage/' . $project->thumbnail));
    }

    public function test_updating_thumbnail_deletes_old_file_and_saves_new_file(): void
    {
        $oldFile = UploadedFile::fake()->image('old_thumb.jpg', 640, 480);
        $oldPath = $oldFile->store('projects/thumbnails', 'public');

        $project = Project::create([
            'title' => 'Project With Old Thumbnail',
            'description' => 'Deskripsi untuk pengujian penggantian thumbnail.',
            'status' => 'published',
            'thumbnail' => $oldPath,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->image('new_thumb.png', 800, 600);

        $response = $this->actingAs($this->admin)->put(route('projects.update', $project), [
            'title' => 'Project With New Thumbnail',
            'description' => 'Deskripsi untuk pengujian penggantian thumbnail terupdate.',
            'status' => 'published',
            'thumbnail' => $newFile,
        ]);

        $response->assertRedirect(route('projects.show', $project));

        $project->refresh();

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($project->thumbnail);
        $this->assertNotSame($oldPath, $project->thumbnail);
    }

    public function test_admin_can_upload_three_gallery_images_simultaneously(): void
    {
        $project = Project::create([
            'title' => 'Gallery Project Test',
            'description' => 'Deskripsi untuk pengujian gallery 3 gambar.',
            'status' => 'published',
        ]);

        $images = [
            UploadedFile::fake()->image('gallery1.jpg', 640, 480),
            UploadedFile::fake()->image('gallery2.jpg', 800, 600),
            UploadedFile::fake()->image('gallery3.png', 1024, 768),
        ];

        $response = $this->actingAs($this->admin)->post(route('projects.gallery.store', $project), [
            'images' => $images,
        ]);

        $response->assertRedirect();

        $this->assertSame(3, $project->images()->count());

        foreach ($project->images as $img) {
            Storage::disk('public')->assertExists($img->image);
        }

        $showResponse = $this->get(route('projects.show', $project));
        $showResponse->assertOk();
        $showResponse->assertSee('3 Gambar');
    }

    public function test_admin_can_delete_one_gallery_image(): void
    {
        $project = Project::create([
            'title' => 'Project Image Deletion Test',
            'description' => 'Deskripsi untuk pengujian hapus gallery.',
            'status' => 'published',
        ]);

        $file = UploadedFile::fake()->image('to_delete.jpg', 640, 480);
        $path = $file->store('projects/gallery', 'public');

        $image = $project->images()->create([
            'image' => $path,
            'sort_order' => 1,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('projects.gallery.destroy', [$project, $image]));
        $response->assertRedirect();

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseMissing('project_images', ['id' => $image->id]);
    }

    public function test_uploading_pdf_file_is_rejected_by_validation(): void
    {
        $pdf = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $thumbResponse = $this->actingAs($this->admin)->post(route('projects.store'), [
            'title' => 'Project Invalid File',
            'description' => 'Deskripsi untuk pengujian penolakan PDF.',
            'status' => 'published',
            'thumbnail' => $pdf,
        ]);

        $thumbResponse->assertSessionHasErrors('thumbnail');

        $project = Project::create([
            'title' => 'Existing Project for Gallery PDF',
            'description' => 'Deskripsi untuk pengujian penolakan PDF pada gallery.',
            'status' => 'published',
        ]);

        $galleryResponse = $this->actingAs($this->admin)->post(route('projects.gallery.store', $project), [
            'images' => [$pdf],
        ]);

        $galleryResponse->assertSessionHasErrors('images.0');
    }

    public function test_uploading_image_with_dimensions_200x200_is_rejected(): void
    {
        $smallImage = UploadedFile::fake()->image('small.jpg', 200, 200);

        $thumbResponse = $this->actingAs($this->admin)->post(route('projects.store'), [
            'title' => 'Small Dimension Project',
            'description' => 'Deskripsi untuk pengujian penolakan gambar kecil.',
            'status' => 'published',
            'thumbnail' => $smallImage,
        ]);

        $thumbResponse->assertSessionHasErrors('thumbnail');

        $project = Project::create([
            'title' => 'Existing Project for Small Gallery',
            'description' => 'Deskripsi untuk pengujian gambar kecil pada gallery.',
            'status' => 'published',
        ]);

        $galleryResponse = $this->actingAs($this->admin)->post(route('projects.gallery.store', $project), [
            'images' => [$smallImage],
        ]);

        $galleryResponse->assertSessionHasErrors('images.0');
    }

    public function test_permanent_delete_cleans_up_thumbnail_and_gallery_files(): void
    {
        $thumbFile = UploadedFile::fake()->image('thumb_perm.jpg', 640, 480);
        $thumbPath = $thumbFile->store('projects/thumbnails', 'public');

        $galleryFile = UploadedFile::fake()->image('gal_perm.jpg', 640, 480);
        $galleryPath = $galleryFile->store('projects/gallery', 'public');

        $project = Project::create([
            'title' => 'Project To Force Delete',
            'description' => 'Deskripsi untuk pengujian force delete file cleanup.',
            'status' => 'published',
            'thumbnail' => $thumbPath,
        ]);

        $project->images()->create([
            'image' => $galleryPath,
            'sort_order' => 1,
        ]);

        $project->delete();

        Storage::disk('public')->assertExists($thumbPath);
        Storage::disk('public')->assertExists($galleryPath);

        $this->actingAs($this->admin)->delete(route('projects.forceDelete', $project->id));

        Storage::disk('public')->assertMissing($thumbPath);
        Storage::disk('public')->assertMissing($galleryPath);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('project_images', ['project_id' => $project->id]);
    }

    public function test_non_admin_cannot_upload_gallery_or_delete_gallery(): void
    {
        $project = Project::create([
            'title' => 'Protected Project Gallery',
            'description' => 'Deskripsi untuk pengujian proteksi rute gallery.',
            'status' => 'published',
        ]);

        $image = $project->images()->create([
            'image' => 'projects/gallery/sample.jpg',
            'sort_order' => 1,
        ]);

        $postResponse = $this->actingAs($this->regularUser)->post(route('projects.gallery.store', $project), [
            'images' => [UploadedFile::fake()->image('attempt.jpg', 640, 480)],
        ]);
        $postResponse->assertForbidden();

        $deleteResponse = $this->actingAs($this->regularUser)->delete(route('projects.gallery.destroy', [$project, $image]));
        $deleteResponse->assertForbidden();
    }
}
