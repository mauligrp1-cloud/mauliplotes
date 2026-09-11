<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Location;
use App\Models\Project;
use App\Models\ProjectFAQ;
use App\Models\ProjectImage;
use App\Models\ProjectNearbyPlace;
use App\Models\ProjectPlotType;
use App\Models\ProjectRERA;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:projects.view')->only(['index', 'show']);
        $this->middleware('permission:projects.create')->only(['create', 'store', 'duplicate']);
        $this->middleware('permission:projects.edit')->only(['edit', 'update', 'reorder', 'storeImage', 'updateImage', 'destroyImage']);
        $this->middleware('permission:projects.delete')->only(['destroy']);
    }

    /**
     * Display a listing of projects with search and filters
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $locationId = $request->query('location_id');
        $status = $request->query('status');
        $featured = $request->query('featured');
        $published = $request->query('published');

        $query = Project::with(['location', 'plotTypes', 'reraRegistrations'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('project_code', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if (!empty($locationId)) {
            $query->where('location_id', $locationId);
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($featured !== null && $featured !== '') {
            $query->where('featured', (bool) $featured);
        }

        if ($published !== null && $published !== '') {
            $query->where('is_published', (bool) $published);
        }

        $projects = $query->paginate(12)->withQueryString();
        $locations = Location::orderBy('name')->get();

        return view('admin.projects.index', compact('projects', 'locations', 'search', 'locationId', 'status', 'featured', 'published'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        $amenities = Amenity::where('is_active', true)->orderBy('name')->get();
        $allProjects = Project::where('is_published', true)->orderBy('name')->get();

        return view('admin.projects.create', compact('locations', 'amenities', 'allProjects'));
    }

    /**
     * Store a newly created project in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Info & Hero
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug',
            'project_code' => 'nullable|string|max:100|unique:projects,project_code',
            'location_id' => 'nullable|exists:locations,id',
            'address' => 'nullable|string|max:255',
            'project_type' => 'required|in:plotted,villa,commercial,mixed',
            'status' => 'required|in:upcoming,active,completed,sold-out',
            'completion_year' => 'nullable|string|max:20',
            'short_description' => 'nullable|string|max:1000',
            'hero_tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',

            // Section Visibility & Hero Components
            'section_visibility' => 'nullable|array',
            'hero_badges' => 'nullable|array',
            'hero_cta' => 'nullable|array',
            'hero_info_card' => 'nullable|array',

            // Overview
            'overview_label' => 'nullable|string|max:255',
            'overview_title' => 'nullable|string|max:255',
            'overview_image' => 'nullable|string|max:500',
            'overview_image_alt' => 'nullable|string|max:255',
            'overview_image_position' => 'nullable|string|in:left,right',
            'overview_punchline' => 'nullable|string|max:255',
            'overview_facts' => 'nullable|array',

            // Pricing & Scale
            'starting_price' => 'nullable|numeric|min:0',
            'price_unit' => 'nullable|string|max:50',
            'display_price' => 'nullable|string|max:255',
            'price_on_request' => 'nullable|boolean',
            'show_price' => 'nullable|boolean',
            'bank_loan_text' => 'nullable|string|max:255',
            'legal_clearances_text' => 'nullable|string|max:255',
            'cta_instant_callback_text' => 'nullable|string|max:255',
            'total_project_area' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:50',
            'total_plots' => 'nullable|integer|min:0',

            // Plot Configurations Section Header
            'plot_configs_heading' => 'nullable|string|max:255',
            'plot_configs_description' => 'nullable|string',
            'plot_configs_cta_text' => 'nullable|string|max:255',

            // Amenities Section Header & Custom Bento
            'amenities_heading' => 'nullable|string|max:255',
            'amenities_description' => 'nullable|string',
            'custom_amenities' => 'nullable|array',

            // Location Advantage & Map
            'location_advantage_heading' => 'nullable|string|max:255',
            'location_advantage_subtext' => 'nullable|string',
            'location_map_heading' => 'nullable|string|max:255',
            'location_map_address' => 'nullable|string|max:255',
            'location_map_url' => 'nullable|string|max:500',

            // Media
            'featured_image' => 'nullable|string|max:500',
            'desktop_hero' => 'nullable|string|max:500',
            'mobile_hero' => 'nullable|string|max:500',
            'master_plan' => 'nullable|string|max:500',
            'layout_map' => 'nullable|string|max:500',
            'location_map' => 'nullable|string|max:500',
            'brochure' => 'nullable|string|max:500',
            'walkthrough_video_url' => 'nullable|string|max:500',
            'video_heading' => 'nullable|string|max:255',
            'video_subtitle' => 'nullable|string|max:255',
            'video_description' => 'nullable|string',
            'video_thumbnail' => 'nullable|string|max:500',
            'video_features' => 'nullable|array',

            // Trust Strip & Specifications
            'trust_strip' => 'nullable|array',
            'specifications_heading' => 'nullable|string|max:255',
            'specifications' => 'nullable|array',

            // FAQs Header
            'faqs_heading' => 'nullable|string|max:255',

            // Final CTA & Related Projects
            'final_cta_heading' => 'nullable|string|max:255',
            'final_cta_description' => 'nullable|string',
            'final_cta_primary_text' => 'nullable|string|max:255',
            'final_cta_secondary_text' => 'nullable|string|max:255',
            'final_cta_image' => 'nullable|string|max:500',
            'related_project_ids' => 'nullable|array',

            // SEO & Publishing
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'canonical_url' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|string|max:500',
            'robots' => 'nullable|string|max:100',
            'is_published' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',

            // Relations arrays
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',

            'plot_types' => 'nullable|array',
            'plot_types.*.name' => 'required_with:plot_types|string|max:255',
            'plot_types.*.size_from' => 'nullable|numeric|min:0',
            'plot_types.*.size_to' => 'nullable|numeric|min:0',
            'plot_types.*.unit' => 'nullable|string|max:50',
            'plot_types.*.price' => 'nullable|numeric|min:0',
            'plot_types.*.availability' => 'nullable|string|max:100',
            'plot_types.*.features' => 'nullable|string|max:255',

            'rera' => 'nullable|array',
            'rera.*.rera_number' => 'required_with:rera|string|max:100',
            'rera.*.phase' => 'nullable|string|max:100',
            'rera.*.rera_url' => 'nullable|string|max:500',
            'rera.*.status' => 'nullable|string|in:approved,registered,under-review,rejected',
            'rera.*.approval_authority' => 'nullable|string|max:255',
            'rera.*.additional_legal_information' => 'nullable|string',

            'nearby_places' => 'nullable|array',
            'nearby_places.*.place_name' => 'required_with:nearby_places|string|max:255',
            'nearby_places.*.category' => 'nullable|string|max:100',
            'nearby_places.*.distance' => 'nullable|string|max:100',
            'nearby_places.*.travel_time' => 'nullable|string|max:100',
            'nearby_places.*.icon' => 'nullable|string|max:100',

            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string',
        ]);

        $projectData = $validated;
        $projectData['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        
        // Ensure slug uniqueness
        $originalSlug = $projectData['slug'];
        $count = 1;
        while (Project::where('slug', $projectData['slug'])->exists()) {
            $projectData['slug'] = $originalSlug . '-' . $count++;
        }

        $projectData['is_published'] = $request->boolean('is_published');
        $projectData['featured'] = $request->boolean('featured');
        $projectData['price_on_request'] = $request->boolean('price_on_request');
        $projectData['show_price'] = $request->boolean('show_price', true);
        $projectData['created_by'] = auth()->id();
        $projectData['updated_by'] = auth()->id();

        // Sanitize JSON arrays & repeaters
        $projectData['section_visibility'] = $this->sanitizeSectionVisibility($request->input('section_visibility'));
        $projectData['hero_badges'] = $this->sanitizeHeroBadges($request->input('hero_badges'));
        $projectData['hero_cta'] = $this->sanitizeHeroCta($request->input('hero_cta'));
        $projectData['hero_info_card'] = $this->sanitizeHeroInfoCard($request->input('hero_info_card'));
        $projectData['overview_facts'] = $this->sanitizeOverviewFacts($request->input('overview_facts'));
        $projectData['trust_strip'] = $this->sanitizeTrustStrip($request->input('trust_strip'));
        $projectData['specifications'] = $this->sanitizeSpecifications($request->input('specifications'));
        $projectData['custom_amenities'] = $this->sanitizeCustomAmenities($request->input('custom_amenities'));
        $projectData['related_project_ids'] = is_array($request->input('related_project_ids')) ? array_map('intval', $request->input('related_project_ids')) : null;

        $project = Project::create($projectData);

        // 1. Sync Amenities
        if ($request->has('amenities')) {
            $project->amenities()->sync($request->input('amenities'));
        }

        // 2. Plot Configurations
        if ($request->has('plot_types') && is_array($request->input('plot_types'))) {
            foreach ($request->input('plot_types') as $idx => $pt) {
                if (!empty($pt['name'])) {
                    $project->plotTypes()->create([
                        'name' => $pt['name'],
                        'size_from' => $pt['size_from'] ?? null,
                        'size_to' => $pt['size_to'] ?? null,
                        'unit' => $pt['unit'] ?? 'sqft',
                        'price' => $pt['price'] ?? null,
                        'availability' => $pt['availability'] ?? 'Available',
                        'features' => $pt['features'] ?? null,
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
        }

        // 3. RERA Registrations
        if ($request->has('rera') && is_array($request->input('rera'))) {
            foreach ($request->input('rera') as $idx => $r) {
                if (!empty($r['rera_number'])) {
                    $project->reraRegistrations()->create([
                        'phase' => $r['phase'] ?? 'Phase 1',
                        'rera_number' => $r['rera_number'],
                        'rera_url' => $r['rera_url'] ?? null,
                        'status' => $r['status'] ?? 'registered',
                        'approval_authority' => $r['approval_authority'] ?? 'MahaRERA',
                        'additional_legal_information' => $r['additional_legal_information'] ?? null,
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
        }

        // 4. Nearby Places
        if ($request->has('nearby_places') && is_array($request->input('nearby_places'))) {
            foreach ($request->input('nearby_places') as $idx => $np) {
                if (!empty($np['place_name'])) {
                    $project->nearbyPlaces()->create([
                        'place_name' => $np['place_name'],
                        'category' => $np['category'] ?? 'Connectivity',
                        'distance' => $np['distance'] ?? null,
                        'travel_time' => $np['travel_time'] ?? null,
                        'icon' => $np['icon'] ?? null,
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
        }

        // 5. FAQs
        if ($request->has('faqs') && is_array($request->input('faqs'))) {
            foreach ($request->input('faqs') as $idx => $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $project->faqs()->create([
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $idx + 1,
                        'is_active' => true,
                    ]);
                }
            }
        }

        // 6. Project Gallery Photography
        if ($request->has('gallery_images') && is_array($request->input('gallery_images'))) {
            foreach ($request->input('gallery_images') as $idx => $g) {
                if (!empty($g['image_path'])) {
                    $cleanImg = preg_replace('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/#', '/storage/', $g['image_path']);
                    $project->images()->create([
                        'type' => 'gallery',
                        'image_path' => $cleanImg,
                        'alt_text' => $g['title'] ?? ($project->name . ' Photography'),
                        'caption' => $g['caption'] ?? null,
                        'sort_order' => (int)($g['sort_order'] ?? ($idx + 1)),
                    ]);
                }
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing the specified project with 10 tabs
     */
    public function edit(Project $project)
    {
        $project->load([
            'location',
            'amenities',
            'plotTypes',
            'reraRegistrations',
            'nearbyPlaces',
            'faqs',
            'images',
        ]);

        $locations = Location::where('is_active', true)->orderBy('name')->get();
        $amenities = Amenity::where('is_active', true)->orderBy('name')->get();
        $allProjects = Project::where('id', '!=', $project->id)->orderBy('name')->get();

        return view('admin.projects.edit', compact('project', 'locations', 'amenities', 'allProjects'));
    }

    /**
     * Update the specified project in storage
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            // Basic Info & Hero
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,' . $project->id,
            'project_code' => 'nullable|string|max:100|unique:projects,project_code,' . $project->id,
            'location_id' => 'nullable|exists:locations,id',
            'address' => 'nullable|string|max:255',
            'project_type' => 'required|in:plotted,villa,commercial,mixed',
            'status' => 'required|in:upcoming,active,completed,sold-out',
            'completion_year' => 'nullable|string|max:20',
            'short_description' => 'nullable|string|max:1000',
            'hero_tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',

            // Section Visibility & Hero Components
            'section_visibility' => 'nullable|array',
            'hero_badges' => 'nullable|array',
            'hero_cta' => 'nullable|array',
            'hero_info_card' => 'nullable|array',

            // Overview
            'overview_label' => 'nullable|string|max:255',
            'overview_title' => 'nullable|string|max:255',
            'overview_image' => 'nullable|string|max:500',
            'overview_image_alt' => 'nullable|string|max:255',
            'overview_image_position' => 'nullable|string|in:left,right',
            'overview_punchline' => 'nullable|string|max:255',
            'overview_facts' => 'nullable|array',

            // Pricing & Scale
            'starting_price' => 'nullable|numeric|min:0',
            'price_unit' => 'nullable|string|max:50',
            'display_price' => 'nullable|string|max:255',
            'price_on_request' => 'nullable|boolean',
            'show_price' => 'nullable|boolean',
            'bank_loan_text' => 'nullable|string|max:255',
            'legal_clearances_text' => 'nullable|string|max:255',
            'cta_instant_callback_text' => 'nullable|string|max:255',
            'total_project_area' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:50',
            'total_plots' => 'nullable|integer|min:0',

            // Plot Configurations Section Header
            'plot_configs_heading' => 'nullable|string|max:255',
            'plot_configs_description' => 'nullable|string',
            'plot_configs_cta_text' => 'nullable|string|max:255',

            // Amenities Section Header & Custom Bento
            'amenities_heading' => 'nullable|string|max:255',
            'amenities_description' => 'nullable|string',
            'custom_amenities' => 'nullable|array',

            // Location Advantage & Map
            'location_advantage_heading' => 'nullable|string|max:255',
            'location_advantage_subtext' => 'nullable|string',
            'location_map_heading' => 'nullable|string|max:255',
            'location_map_address' => 'nullable|string|max:255',
            'location_map_url' => 'nullable|string|max:500',

            // Media
            'featured_image' => 'nullable|string|max:500',
            'desktop_hero' => 'nullable|string|max:500',
            'mobile_hero' => 'nullable|string|max:500',
            'master_plan' => 'nullable|string|max:500',
            'layout_map' => 'nullable|string|max:500',
            'location_map' => 'nullable|string|max:500',
            'brochure' => 'nullable|string|max:500',
            'walkthrough_video_url' => 'nullable|string|max:500',
            'video_heading' => 'nullable|string|max:255',
            'video_subtitle' => 'nullable|string|max:255',
            'video_description' => 'nullable|string',
            'video_thumbnail' => 'nullable|string|max:500',
            'video_features' => 'nullable|array',

            // Trust Strip & Specifications
            'trust_strip' => 'nullable|array',
            'specifications_heading' => 'nullable|string|max:255',
            'specifications' => 'nullable|array',

            // FAQs Header
            'faqs_heading' => 'nullable|string|max:255',

            // Final CTA & Related Projects
            'final_cta_heading' => 'nullable|string|max:255',
            'final_cta_description' => 'nullable|string',
            'final_cta_primary_text' => 'nullable|string|max:255',
            'final_cta_secondary_text' => 'nullable|string|max:255',
            'final_cta_image' => 'nullable|string|max:500',
            'related_project_ids' => 'nullable|array',

            // SEO & Publishing
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'canonical_url' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|string|max:500',
            'robots' => 'nullable|string|max:100',
            'is_published' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',

            // Relations arrays
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',

            'plot_types' => 'nullable|array',
            'plot_types.*.name' => 'required_with:plot_types|string|max:255',
            'plot_types.*.size_from' => 'nullable|numeric|min:0',
            'plot_types.*.size_to' => 'nullable|numeric|min:0',
            'plot_types.*.unit' => 'nullable|string|max:50',
            'plot_types.*.price' => 'nullable|numeric|min:0',
            'plot_types.*.availability' => 'nullable|string|max:100',
            'plot_types.*.features' => 'nullable|string|max:255',

            'rera' => 'nullable|array',
            'rera.*.rera_number' => 'required_with:rera|string|max:100',
            'rera.*.phase' => 'nullable|string|max:100',
            'rera.*.rera_url' => 'nullable|string|max:500',
            'rera.*.status' => 'nullable|string|in:approved,registered,under-review,rejected',
            'rera.*.approval_authority' => 'nullable|string|max:255',
            'rera.*.additional_legal_information' => 'nullable|string',

            'nearby_places' => 'nullable|array',
            'nearby_places.*.place_name' => 'required_with:nearby_places|string|max:255',
            'nearby_places.*.category' => 'nullable|string|max:100',
            'nearby_places.*.distance' => 'nullable|string|max:100',
            'nearby_places.*.travel_time' => 'nullable|string|max:100',
            'nearby_places.*.icon' => 'nullable|string|max:100',

            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string',
        ]);

        $projectData = $validated;
        $projectData['is_published'] = $request->boolean('is_published');
        $projectData['featured'] = $request->boolean('featured');
        $projectData['price_on_request'] = $request->boolean('price_on_request');
        $projectData['show_price'] = $request->boolean('show_price', true);
        $projectData['updated_by'] = auth()->id();

        // Sanitize JSON arrays & repeaters
        $projectData['section_visibility'] = $this->sanitizeSectionVisibility($request->input('section_visibility'));
        $projectData['hero_badges'] = $this->sanitizeHeroBadges($request->input('hero_badges'));
        $projectData['hero_cta'] = $this->sanitizeHeroCta($request->input('hero_cta'));
        $projectData['hero_info_card'] = $this->sanitizeHeroInfoCard($request->input('hero_info_card'));
        $projectData['overview_facts'] = $this->sanitizeOverviewFacts($request->input('overview_facts'));
        $projectData['trust_strip'] = $this->sanitizeTrustStrip($request->input('trust_strip'));
        $projectData['specifications'] = $this->sanitizeSpecifications($request->input('specifications'));
        $projectData['custom_amenities'] = $this->sanitizeCustomAmenities($request->input('custom_amenities'));
        $projectData['related_project_ids'] = is_array($request->input('related_project_ids')) ? array_map('intval', $request->input('related_project_ids')) : null;

        $project->update($projectData);

        // 1. Sync Amenities
        $project->amenities()->sync($request->input('amenities', []));

        // 2. Plot Configurations Sync
        $project->plotTypes()->delete();
        if ($request->has('plot_types') && is_array($request->input('plot_types'))) {
            foreach ($request->input('plot_types') as $idx => $pt) {
                if (!empty($pt['name'])) {
                    $project->plotTypes()->create([
                        'name' => $pt['name'],
                        'size_from' => $pt['size_from'] ?? null,
                        'size_to' => $pt['size_to'] ?? null,
                        'unit' => $pt['unit'] ?? 'sqft',
                        'price' => $pt['price'] ?? null,
                        'availability' => $pt['availability'] ?? 'Available',
                        'features' => $pt['features'] ?? null,
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
        }

        // 3. RERA Registrations Sync
        $project->reraRegistrations()->delete();
        if ($request->has('rera') && is_array($request->input('rera'))) {
            foreach ($request->input('rera') as $idx => $r) {
                if (!empty($r['rera_number'])) {
                    $project->reraRegistrations()->create([
                        'phase' => $r['phase'] ?? 'Phase 1',
                        'rera_number' => $r['rera_number'],
                        'rera_url' => $r['rera_url'] ?? null,
                        'status' => $r['status'] ?? 'registered',
                        'approval_authority' => $r['approval_authority'] ?? 'MahaRERA',
                        'additional_legal_information' => $r['additional_legal_information'] ?? null,
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
        }

        // 4. Nearby Places Sync
        $project->nearbyPlaces()->delete();
        if ($request->has('nearby_places') && is_array($request->input('nearby_places'))) {
            foreach ($request->input('nearby_places') as $idx => $np) {
                if (!empty($np['place_name'])) {
                    $project->nearbyPlaces()->create([
                        'place_name' => $np['place_name'],
                        'category' => $np['category'] ?? 'Connectivity',
                        'distance' => $np['distance'] ?? null,
                        'travel_time' => $np['travel_time'] ?? null,
                        'icon' => $np['icon'] ?? null,
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
        }

        // 5. FAQs Sync
        $project->faqs()->delete();
        if ($request->has('faqs') && is_array($request->input('faqs'))) {
            foreach ($request->input('faqs') as $idx => $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $project->faqs()->create([
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $idx + 1,
                        'is_active' => true,
                    ]);
                }
            }
        }

        // 6. Project Gallery Photography Sync
        $project->images()->where('type', 'gallery')->delete();
        if ($request->has('gallery_images') && is_array($request->input('gallery_images'))) {
            foreach ($request->input('gallery_images') as $idx => $g) {
                if (!empty($g['image_path'])) {
                    $cleanImg = preg_replace('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/#', '/storage/', $g['image_path']);
                    $project->images()->create([
                        'type' => 'gallery',
                        'image_path' => $cleanImg,
                        'alt_text' => $g['title'] ?? ($project->name . ' Photography'),
                        'caption' => $g['caption'] ?? null,
                        'sort_order' => (int)($g['sort_order'] ?? ($idx + 1)),
                    ]);
                }
            }
        }

        return redirect()->route('admin.projects.edit', $project)->with('success', 'Project updated successfully.');
    }

    /**
     * Sanitize Section Visibility
     */
    protected function sanitizeSectionVisibility(?array $vis): array
    {
        $defaults = [
            'hero' => 1,
            'trust_strip' => 1,
            'overview' => 1,
            'plot_configs' => 1,
            'amenities' => 1,
            'location_advantage' => 1,
            'location_map' => 1,
            'gallery' => 1,
            'specifications' => 1,
            'faqs' => 1,
            'related_projects' => 1,
            'final_cta' => 1,
        ];

        if (empty($vis) || !is_array($vis)) {
            return $defaults;
        }

        $res = [];
        foreach ($defaults as $sec => $def) {
            $res[$sec] = isset($vis[$sec]) ? (int)(bool)$vis[$sec] : 0;
        }
        return $res;
    }

    /**
     * Sanitize Dynamic Hero Badges
     */
    protected function sanitizeHeroBadges(?array $items): ?array
    {
        if (empty($items) || !is_array($items)) {
            return null;
        }

        $cleaned = [];
        foreach ($items as $item) {
            if (!empty($item['name'])) {
                $cleaned[] = [
                    'name' => trim($item['name']),
                    'style' => !empty($item['style']) ? trim($item['style']) : 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                    'dot' => !empty($item['dot']) ? trim($item['dot']) : '#10B981',
                    'active' => !empty($item['active']),
                ];
            }
        }

        return count($cleaned) > 0 ? $cleaned : null;
    }

    /**
     * Sanitize Hero CTA Buttons
     */
    protected function sanitizeHeroCta(?array $data): ?array
    {
        if (empty($data) || !is_array($data)) {
            return null;
        }

        return [
            'primary' => [
                'active' => !empty($data['primary']['active']),
                'text' => trim($data['primary']['text'] ?? 'Book Free Site Visit'),
                'type' => $data['primary']['type'] ?? 'site_visit_modal',
                'icon' => $data['primary']['icon'] ?? 'bi-calendar-event',
                'color' => $data['primary']['color'] ?? 'primary',
            ],
            'secondary' => [
                'active' => !empty($data['secondary']['active']),
                'text' => trim($data['secondary']['text'] ?? 'Download E-Brochure'),
                'type' => $data['secondary']['type'] ?? 'brochure_download',
                'brochure_url' => trim($data['secondary']['brochure_url'] ?? ''),
                'icon' => $data['secondary']['icon'] ?? 'bi-download',
            ],
            'tertiary' => [
                'active' => !empty($data['tertiary']['active']),
                'text' => trim($data['tertiary']['text'] ?? 'Request Instant Call Back'),
                'type' => $data['tertiary']['type'] ?? 'callback_form',
                'icon' => $data['tertiary']['icon'] ?? 'bi-telephone-fill',
                'color' => $data['tertiary']['color'] ?? 'secondary',
            ]
        ];
    }

    /**
     * Sanitize Hero Investment Info Card
     */
    protected function sanitizeHeroInfoCard(?array $data): ?array
    {
        if (empty($data) || !is_array($data)) {
            return null;
        }

        $rows = [];
        if (!empty($data['rows']) && is_array($data['rows'])) {
            foreach ($data['rows'] as $r) {
                if (!empty($r['label']) || !empty($r['value'])) {
                    $rows[] = [
                        'label' => trim($r['label'] ?? ''),
                        'value' => trim($r['value'] ?? ''),
                    ];
                }
            }
        }

        return [
            'show_card' => !empty($data['show_card']),
            'label' => trim($data['label'] ?? 'INVESTMENT SIZING'),
            'price_text' => trim($data['price_text'] ?? ''),
            'sub_text' => trim($data['sub_text'] ?? 'Bank Loan Approved by Major Banks'),
            'rows' => $rows,
        ];
    }

    /**
     * Sanitize Overview Quick Facts
     */
    protected function sanitizeOverviewFacts(?array $items): ?array
    {
        if (empty($items) || !is_array($items)) {
            return null;
        }

        $cleaned = [];
        foreach ($items as $item) {
            if (!empty($item['label']) || !empty($item['value'])) {
                $cleaned[] = [
                    'value' => trim($item['value'] ?? ''),
                    'suffix' => trim($item['suffix'] ?? ''),
                    'label' => trim($item['label'] ?? ''),
                    'icon' => trim($item['icon'] ?? ''),
                ];
            }
        }

        return count($cleaned) > 0 ? $cleaned : null;
    }

    /**
     * Sanitize Trust Strip Items
     */
    protected function sanitizeTrustStrip(?array $items): ?array
    {
        if (empty($items) || !is_array($items)) {
            return null;
        }

        $cleaned = [];
        foreach ($items as $item) {
            if (!empty($item['title'])) {
                $cleaned[] = [
                    'icon' => $item['icon'] ?? 'bi-shield-check',
                    'title' => trim($item['title']),
                    'subtitle' => trim($item['subtitle'] ?? ''),
                    'link' => trim($item['link'] ?? ''),
                    'active' => !empty($item['active']),
                ];
            }
        }

        return count($cleaned) > 0 ? $cleaned : null;
    }

    /**
     * Sanitize Project Specifications Table Rows
     */
    protected function sanitizeSpecifications(?array $items): ?array
    {
        if (empty($items) || !is_array($items)) {
            return null;
        }

        $cleaned = [];
        foreach ($items as $item) {
            if (!empty($item['label']) && !empty($item['value'])) {
                $cleaned[] = [
                    'label' => trim($item['label']),
                    'value' => trim($item['value']),
                    'highlight' => !empty($item['highlight']),
                ];
            }
        }

        return count($cleaned) > 0 ? $cleaned : null;
    }

    /**
     * Sanitize Custom Amenities Bento Grid Items
     */
    protected function sanitizeCustomAmenities(?array $items): ?array
    {
        if (empty($items) || !is_array($items)) {
            return null;
        }

        $cleaned = [];
        foreach ($items as $idx => $item) {
            if (!empty($item['title']) || !empty($item['image'])) {
                $cleanedImg = preg_replace('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/#', '/storage/', $item['image'] ?? '');
                $cleaned[] = [
                    'badge' => !empty($item['badge']) ? $item['badge'] : sprintf('%02d', $idx + 1),
                    'title' => trim($item['title'] ?? ''),
                    'description' => trim($item['description'] ?? ''),
                    'image' => $cleanedImg,
                    'icon' => $item['icon'] ?? '',
                    'card_size' => $item['card_size'] ?? 'medium',
                    'active' => !empty($item['active']),
                ];
            }
        }

        return count($cleaned) > 0 ? $cleaned : null;
    }

    /**
     * Duplicate a project with all child configurations
     */
    public function duplicate(Project $project)
    {
        $newProject = $project->replicate(['created_at', 'updated_at']);
        $newProject->name = $project->name . ' (Copy)';
        $newProject->slug = Str::slug($project->name) . '-copy-' . time();
        $newProject->project_code = $project->project_code ? ($project->project_code . '-COPY') : null;
        $newProject->is_published = false;
        $newProject->created_by = auth()->id();
        $newProject->updated_by = auth()->id();
        $newProject->save();

        // Replicate Amenities
        $newProject->amenities()->sync($project->amenities->pluck('id')->toArray());

        // Replicate Plot Types
        foreach ($project->plotTypes as $pt) {
            $newPlot = $pt->replicate(['project_id']);
            $newProject->plotTypes()->save($newPlot);
        }

        // Replicate RERA
        foreach ($project->reraRegistrations as $r) {
            $newRera = $r->replicate(['project_id']);
            $newProject->reraRegistrations()->save($newRera);
        }

        // Replicate Nearby Places
        foreach ($project->nearbyPlaces as $np) {
            $newNp = $np->replicate(['project_id']);
            $newProject->nearbyPlaces()->save($newNp);
        }

        // Replicate FAQs
        foreach ($project->faqs as $faq) {
            $newFaq = $faq->replicate(['project_id']);
            $newProject->faqs()->save($newFaq);
        }

        // Replicate Gallery Images
        foreach ($project->images as $img) {
            $newImg = $img->replicate(['project_id']);
            $newProject->images()->save($newImg);
        }

        return redirect()->route('admin.projects.edit', $newProject)->with('success', 'Project duplicated successfully as a draft.');
    }

    /**
     * Remove the specified project from storage
     */
    public function destroy(Project $project)
    {
        $name = $project->name;
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', "Project '{$name}' deleted successfully.");
    }

    /**
     * Store a new image for a project (called via AJAX from amenities tab)
     */
    public function storeImage(Request $request, Project $project)
    {
        $validated = $request->validate([
            'type'       => 'required|string|in:amenity,gallery,hero,other',
            'image_path' => 'required|string',
            'caption'    => 'nullable|string|max:255',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:1',
        ]);

        $image = $project->images()->create([
            'type'       => $validated['type'],
            'image_path' => $validated['image_path'],
            'caption'    => $validated['caption'] ?? null,
            'alt_text'   => $validated['alt_text'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ($project->images()->where('type', $validated['type'])->count() + 1),
        ]);

        return response()->json([
            'success' => true,
            'image'   => $image,
        ]);
    }

    /**
     * Update image metadata (caption, alt_text, sort_order) via AJAX
     */
    public function updateImage(Request $request, Project $project, \App\Models\ProjectImage $image)
    {
        if ($image->project_id !== $project->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'caption'    => 'nullable|string|max:255',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:1',
        ]);

        $image->update(array_filter($validated, fn($v) => $v !== null));

        return response()->json(['success' => true, 'image' => $image->fresh()]);
    }

    /**
     * Delete a project image via AJAX
     */
    public function destroyImage(Project $project, \App\Models\ProjectImage $image)
    {
        if ($image->project_id !== $project->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Reorder projects sequence (called via AJAX or regular form submission)
     */
    public function reorder(Request $request)
    {
        $orders = $request->input('orders', []);

        if (empty($orders) && $request->has('sequence')) {
            $orders = $request->input('sequence');
        }

        if (is_array($orders)) {
            foreach ($orders as $key => $val) {
                // If format is [{id: 1, sort_order: 1}, ...]
                if (is_array($val) && isset($val['id'])) {
                    $id = (int)$val['id'];
                    $sortOrder = isset($val['sort_order']) ? (int)$val['sort_order'] : (int)($key + 1);
                } elseif (is_numeric($key) && is_numeric($val)) {
                    // Could be [id => sort_order]
                    $id = (int)$key;
                    $sortOrder = (int)$val;
                } else {
                    continue;
                }

                if ($id > 0) {
                    Project::where('id', $id)->update(['sort_order' => $sortOrder]);
                }
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project display sequence saved successfully!'
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project display sequence saved successfully!');
    }
}


