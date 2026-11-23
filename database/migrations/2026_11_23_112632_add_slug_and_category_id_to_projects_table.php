<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug', 160)->nullable()->unique()->after('title');
            $table->foreignId('category_id')->nullable()->after('slug')->constrained()->nullOnDelete();
        });

        // Backfill categories and unique slugs only when existing projects are present.
        if (! DB::table('projects')->exists()) {
            return;
        }

        $now = now();
        $defaultCategories = [
            ['name' => 'Web Development', 'slug' => 'web-development', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mobile App', 'slug' => 'mobile-app', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Backend & Services', 'slug' => 'backend-services', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'IoT & Automation', 'slug' => 'iot-automation', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($defaultCategories as $cat) {
            DB::table('categories')->updateOrInsert(['slug' => $cat['slug']], $cat);
        }

        $categoryIds = DB::table('categories')->pluck('id', 'slug');

        $techToCategorySlug = [
            'PHP' => 'web-development',
            'Laravel' => 'web-development',
            'React' => 'web-development',
            'Vue.js' => 'web-development',
            'Next.js' => 'web-development',
            'Flutter' => 'mobile-app',
            'Kotlin' => 'mobile-app',
            'Node.js' => 'backend-services',
            'Java' => 'backend-services',
            'Python' => 'iot-automation',
        ];

        $usedSlugs = [];
        $projects = DB::table('projects')->orderBy('id')->get(['id', 'title', 'technology']);

        foreach ($projects as $project) {
            $baseSlug = Str::slug($project->title) ?: 'project-' . $project->id;
            $slug = $baseSlug;
            $counter = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $usedSlugs[] = $slug;
            $categorySlug = $techToCategorySlug[$project->technology] ?? 'web-development';

            DB::table('projects')
                ->where('id', $project->id)
                ->update([
                    'slug' => $slug,
                    'category_id' => $categoryIds[$categorySlug] ?? null,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn('slug');
        });
    }
};
