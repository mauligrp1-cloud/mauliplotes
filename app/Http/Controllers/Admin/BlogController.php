<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:blog.view')->only(['index', 'show']);
        $this->middleware('permission:blog.create')->only(['create', 'store']);
        $this->middleware('permission:blog.edit')->only(['edit', 'update']);
        $this->middleware('permission:blog.delete')->only(['destroy']);
    }

    /**
     * Sanitize rich-text HTML against XSS payloads
     */
    protected function sanitizeHtml(?string $html): string
    {
        if (empty($html)) {
            return '';
        }
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $html);
        $html = preg_replace('#<object(.*?)>(.*?)</object>#is', '', $html);
        $html = preg_replace('#<embed(.*?)>(.*?)</embed>#is', '', $html);
        $html = preg_replace('#\s*on[a-zA-Z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]*)#i', '', $html);
        $html = preg_replace('#([a-zA-Z]+)\s*=\s*("|\')\s*javascript:[^"\']*("|\')#i', '$1="#"', $html);
        return $html;
    }

    /**
     * Display listing of blog articles
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $category = $request->query('category');

        $query = BlogPost::with(['categories', 'author'])->latest('published_at')->latest('id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('focus_keyword', 'like', "%{$search}%");
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($category)) {
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('slug', $category)->orWhere('blog_categories.id', $category);
            });
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = BlogCategory::withCount('posts')->get();

        return view('admin.blogs.index', compact('posts', 'categories', 'search', 'status', 'category'));
    }

    /**
     * Show form to create new post
     */
    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();
        $allProjects = Project::where('is_published', true)->orderBy('name')->get(['id', 'name', 'slug', 'starting_price', 'location_id']);
        $users = User::orderBy('name')->get();

        return view('admin.blogs.create', compact('categories', 'tags', 'allProjects', 'users'));
    }

    /**
     * Store new blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|string|max:500',
            'body' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'reading_time' => 'nullable|integer|min:1|max:120',

            // Author details
            'author_name' => 'nullable|string|max:255',
            'author_role' => 'nullable|string|max:255',
            'author_avatar' => 'nullable|string|max:500',

            // SEO Meta Suite
            'focus_keyword' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'canonical_url' => 'nullable|string|max:500',
            'robots' => 'nullable|string|max:100',

            // Social OpenGraph
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|string|max:500',

            // Real Estate Lead Conversion & Related Projects
            'related_project_ids' => 'nullable|array',
            'enable_cta_box' => 'nullable|boolean',
            'cta_heading' => 'nullable|string|max:255',
            'cta_description' => 'nullable|string|max:1000',
            'cta_button_text' => 'nullable|string|max:100',
            'cta_button_url' => 'nullable|string|max:500',

            // Taxonomies
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id',
            'tags_input' => 'nullable|string|max:500',
        ]);

        $validated['body'] = $this->sanitizeHtml($validated['body']);
        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['created_by'] = auth()->id();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['enable_cta_box'] = $request->boolean('enable_cta_box', true);
        $validated['related_project_ids'] = is_array($request->input('related_project_ids')) ? array_map('intval', $request->input('related_project_ids')) : null;

        if ($validated['status'] === 'published') {
            $validated['published_at'] = !empty($request->input('published_at')) ? $request->input('published_at') : now();
        } else {
            $validated['published_at'] = !empty($request->input('published_at')) ? $request->input('published_at') : null;
        }

        // Auto-calculate reading time if not manually given
        if (empty($validated['reading_time'])) {
            $wordCount = str_word_count(strip_tags($validated['body'] ?? ''));
            $validated['reading_time'] = max(1, (int) ceil($wordCount / 200));
        }

        $post = BlogPost::create($validated);

        if ($request->has('categories')) {
            $post->categories()->sync($request->input('categories'));
        }

        // Sync or create tags from comma-separated input
        if ($request->filled('tags_input')) {
            $tagNames = array_filter(array_map('trim', explode(',', $request->input('tags_input'))));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = BlogTag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Article published and SEO meta configured successfully.');
    }

    /**
     * Show form to edit post
     */
    public function edit(BlogPost $blog)
    {
        $post = $blog->load(['categories', 'tags']);
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();
        $allProjects = Project::where('is_published', true)->orderBy('name')->get(['id', 'name', 'slug', 'starting_price', 'location_id']);
        $users = User::orderBy('name')->get();

        return view('admin.blogs.edit', compact('post', 'categories', 'tags', 'allProjects', 'users'));
    }

    /**
     * Update blog post
     */
    public function update(Request $request, BlogPost $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'excerpt' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|string|max:500',
            'body' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'reading_time' => 'nullable|integer|min:1|max:120',

            // Author details
            'author_name' => 'nullable|string|max:255',
            'author_role' => 'nullable|string|max:255',
            'author_avatar' => 'nullable|string|max:500',

            // SEO Meta Suite
            'focus_keyword' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'canonical_url' => 'nullable|string|max:500',
            'robots' => 'nullable|string|max:100',

            // Social OpenGraph
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|string|max:500',

            // Real Estate Lead Conversion & Related Projects
            'related_project_ids' => 'nullable|array',
            'enable_cta_box' => 'nullable|boolean',
            'cta_heading' => 'nullable|string|max:255',
            'cta_description' => 'nullable|string|max:1000',
            'cta_button_text' => 'nullable|string|max:100',
            'cta_button_url' => 'nullable|string|max:500',

            // Taxonomies
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id',
            'tags_input' => 'nullable|string|max:500',
        ]);

        $validated['body'] = $this->sanitizeHtml($validated['body']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['enable_cta_box'] = $request->boolean('enable_cta_box', true);
        $validated['related_project_ids'] = is_array($request->input('related_project_ids')) ? array_map('intval', $request->input('related_project_ids')) : null;

        if ($validated['status'] === 'published' && empty($request->input('published_at')) && !$blog->published_at) {
            $validated['published_at'] = now();
        } elseif (!empty($request->input('published_at'))) {
            $validated['published_at'] = $request->input('published_at');
        }

        // Auto-calculate reading time if not manually given
        if (empty($validated['reading_time'])) {
            $wordCount = str_word_count(strip_tags($validated['body'] ?? ''));
            $validated['reading_time'] = max(1, (int) ceil($wordCount / 200));
        }

        $blog->update($validated);

        // Sync categories
        $blog->categories()->sync($request->input('categories', []));

        // Sync tags
        if ($request->has('tags_input')) {
            $tagNames = array_filter(array_map('trim', explode(',', $request->input('tags_input', ''))));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = BlogTag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $blog->tags()->sync($tagIds);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Article updated and SEO settings synchronized.');
    }

    /**
     * Delete blog post
     */
    public function destroy(BlogPost $blog)
    {
        $blog->categories()->detach();
        $blog->tags()->detach();
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Article deleted successfully.');
    }
}
