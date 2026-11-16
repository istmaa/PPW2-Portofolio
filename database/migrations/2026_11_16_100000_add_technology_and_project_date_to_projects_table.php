<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('technology')->nullable()->after('status');
            $table->date('project_date')->nullable()->after('technology');
        });

        // Seed realistic technologies, dates, and statuses across existing projects
        $technologies = ['Laravel', 'React', 'Vue.js', 'Flutter', 'Python', 'Tailwind CSS'];
        $projects = DB::table('projects')->get();

        foreach ($projects as $index => $project) {
            $tech = $technologies[$index % count($technologies)];
            $date = date('Y-m-d', strtotime("2026-01-10 + " . ($index * 5) . " days"));
            $status = ($index % 8 === 0) ? 'draft' : $project->status;

            DB::table('projects')->where('id', $project->id)->update([
                'technology' => $tech,
                'project_date' => $date,
                'status' => $status,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['technology', 'project_date']);
        });
    }
};
