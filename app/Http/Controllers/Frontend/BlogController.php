<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display listing of published blog posts
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categorySlug = $request->query('category');

        $query = BlogPost::with(['categories', 'author', 'tags'])
            ->where('status', 'published')
            ->latest('published_at');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('focus_keyword', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if (!empty($categorySlug)) {
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $featuredPost = null;
        if (empty($search) && empty($categorySlug)) {
            $featuredPost = BlogPost::with(['categories', 'author'])
                ->where('status', 'published')
                ->where('is_featured', true)
                ->latest('published_at')
                ->first();
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = BlogCategory::withCount(['posts' => function ($q) {
            $q->where('status', 'published');
        }])->get();

        return view('frontend.blog.index', compact('posts', 'categories', 'search', 'categorySlug', 'featuredPost'));
    }

    /**
     * Display a single blog article with SEO and Related Projects
     */
    public function show($slug)
    {
        $post = BlogPost::with(['categories', 'tags', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $post->increment('views_count');

        // Fetch related articles
        $relatedPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        // Fetch related projects
        $relatedProjects = $post->related_projects_list;
        if ($relatedProjects->isEmpty()) {
            $relatedProjects = Project::where('is_published', true)
                ->with(['location'])
                ->take(3)
                ->get();
        }

        return view('frontend.blog.show', compact('post', 'relatedPosts', 'relatedProjects'));
    }
}
