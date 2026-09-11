<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Location;
use App\Models\Page;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Homepage
     */
    public function home()
    {
        $page     = Page::with('sections')->where('slug', 'home')->first();
        $sections = $page
            ? $page->sections->where('is_active', true)->keyBy('section_key')
            : collect();

        $featuredProjects = Project::with(['location', 'plotTypes', 'reraRegistrations'])
            ->where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($featuredProjects->isEmpty()) {
            $featuredProjects = Project::with(['location', 'plotTypes', 'reraRegistrations'])
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        $locations = Location::where('is_active', true)
            ->withCount('projects')
            ->orderBy('name')
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $latestPosts = BlogPost::with(['categories', 'author'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $galleryItems = GalleryItem::with('project')->latest()->take(6)->get();

        return view('frontend.home', compact('page', 'sections', 'featuredProjects', 'locations', 'testimonials', 'latestPosts', 'galleryItems'));
    }

    /**
     * About Us page
     */
    public function about()
    {
        $page     = Page::with('sections')->where('slug', 'about')->first();
        $sections = $page
            ? $page->sections->where('is_active', true)->sortBy('sort_order')->keyBy('section_key')
            : collect();

        $team         = TeamMember::where('is_active', true)->orderBy('sort_order')->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->take(6)->get();

        return view('frontend.pages.about', compact('page', 'sections', 'team', 'testimonials'));
    }

    /**
     * Why Invest in Nagpur page (Redirect to Homepage section)
     */
    public function whyNagpur()
    {
        return redirect()->route('home');
    }

    /**
     * NRI Investor Desk page (Redirect to Homepage)
     */
    public function nri()
    {
        return redirect()->route('home');
    }

    /**
     * Contact Us & Site Visit Booking page
     */
    public function contact()
    {
        $page     = Page::with('sections')->where('slug', 'contact')->first();
        $sections = $page
            ? $page->sections->where('is_active', true)->sortBy('sort_order')->keyBy('section_key')
            : collect();

        $projects = Project::where('is_published', true)->orderBy('name')->get();

        return view('frontend.pages.contact', compact('page', 'sections', 'projects'));
    }

    public function gallery(Request $request)
    {
        // Load all categories with their active gallery items ordered by sort_order
        $categories = \App\Models\GalleryCategory::with(['galleries' => function ($q) {
            $q->with(['activeItems' => function ($qi) {
                $qi->orderBy('sort_order');
            }]);
        }])->get()->map(function ($cat) {
            // Flatten all active items across galleries within this category
            $cat->flatItems = $cat->galleries->flatMap(fn($g) => $g->activeItems)->sortBy('sort_order')->values();
            return $cat;
        })->filter(fn($cat) => $cat->flatItems->isNotEmpty());

        // All items for "All" tab
        $allItems = \App\Models\GalleryItem::with(['gallery.category'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $totalCount = $allItems->count();

        return view('frontend.pages.gallery', compact('categories', 'allItems', 'totalCount'));
    }


    /**
     * Team Page (or redirect to About Us team section)
     */
    public function team()
    {
        return redirect()->to(url('/about#team'));
    }

    /**
     * Privacy Policy Page
     */
    public function privacyPolicy()
    {
        $page = Page::where('slug', 'privacy-policy')->first();
        return view('frontend.pages.legal', [
            'title' => 'Privacy Policy',
            'type'  => 'privacy',
            'page'  => $page
        ]);
    }

    /**
     * Terms & Conditions Page
     */
    public function termsConditions()
    {
        $page = Page::where('slug', 'terms-conditions')->first();
        return view('frontend.pages.legal', [
            'title' => 'Terms & Conditions',
            'type'  => 'terms',
            'page'  => $page
        ]);
    }
}
