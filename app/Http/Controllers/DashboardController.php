<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $publishedProjects = Project::where('status', 'published')->count();
        $draftProjects = Project::where('status', 'draft')->count();
        $totalCategories = Category::count();
        $totalMessages = Contact::count();
        $unreadMessages = Contact::where('is_read', false)->count();

        $recentContacts = Contact::latest()->orderByDesc('id')->take(5)->get();

        return view('posts.dashboard', compact(
            'totalProjects',
            'publishedProjects',
            'draftProjects',
            'totalCategories',
            'totalMessages',
            'unreadMessages',
            'recentContacts'
        ));
    }
}
