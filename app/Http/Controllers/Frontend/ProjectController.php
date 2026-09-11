<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display public catalog of plotted projects with filter criteria
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $locationSlug = $request->query('location');
        $projectType = $request->query('type');
        $status = $request->query('status');
        $budget = $request->query('budget');
        $sort = $request->query('sort', 'featured');

        $query = Project::with(['location', 'plotTypes', 'reraRegistrations'])
            ->where('is_published', true);

        // Keyword Search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('project_code', 'like', "%{$search}%");
            });
        }

        // Filter by Location
        if (!empty($locationSlug)) {
            $query->whereHas('location', function ($q) use ($locationSlug) {
                $q->where('slug', $locationSlug);
            });
        }

        // Filter by Project Type
        if (!empty($projectType) && $projectType !== 'all') {
            $query->where('project_type', $projectType);
        }

        // Filter by Status
        if (!empty($status) && $status !== 'all') {
            if ($status === 'new' || $status === 'upcoming') {
                $query->whereIn('status', ['new', 'upcoming']);
            } elseif ($status === 'ongoing' || $status === 'active') {
                $query->whereIn('status', ['active', 'ongoing']);
            } else {
                $query->where('status', $status);
            }
        }

        // Filter by Budget Range
        if (!empty($budget)) {
            switch ($budget) {
                case 'under-25L':
                    $query->where('starting_price', '<', 2500000);
                    break;
                case '25L-50L':
                    $query->whereBetween('starting_price', [2500000, 5000000]);
                    break;
                case '50L-1Cr':
                    $query->whereBetween('starting_price', [5000000, 10000000]);
                    break;
                case 'above-1Cr':
                    $query->where('starting_price', '>=', 10000000);
                    break;
            }
        }

        // Sorting
        switch ($sort) {
            case 'price-low':
                $query->orderBy('starting_price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('starting_price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'featured':
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('featured', 'desc')->orderBy('id', 'asc');
                break;
        }

        $projects = $query->paginate(12)->withQueryString();
        $locations = Location::where('is_active', true)->withCount('projects')->get();
        $totalProjectsCount = Project::where('is_published', true)->count();

        $statusCounts = [
            'all' => $totalProjectsCount,
            'new' => Project::where('is_published', true)->whereIn('status', ['new', 'upcoming'])->count(),
            'ongoing' => Project::where('is_published', true)->whereIn('status', ['active', 'ongoing'])->count(),
            'completed' => Project::where('is_published', true)->where('status', 'completed')->count(),
        ];

        return view('frontend.projects.index', compact(
            'projects',
            'locations',
            'search',
            'locationSlug',
            'projectType',
            'status',
            'budget',
            'sort',
            'totalProjectsCount',
            'statusCounts'
        ));
    }

    /**
     * Display a single project detail page
     */
    public function show($slug)
    {
        $project = Project::with([
            'location',
            'amenities',
            'plotTypes',
            'reraRegistrations',
            'nearbyPlaces',
            'faqs',
            'images'
        ])
        ->where('slug', $slug)
        ->where('is_published', true)
        ->firstOrFail();

        // Related projects: from custom selected IDs or location fallback
        $relatedProjects = collect();
        if (!empty($project->related_project_ids) && is_array($project->related_project_ids)) {
            $relatedProjects = Project::with(['location', 'plotTypes'])
                ->whereIn('id', $project->related_project_ids)
                ->where('is_published', true)
                ->get();
        }

        if ($relatedProjects->isEmpty()) {
            $relatedProjects = Project::with(['location', 'plotTypes'])
                ->where('is_published', true)
                ->where('id', '!=', $project->id)
                ->when($project->location_id, function($q) use ($project) {
                    $q->where('location_id', $project->location_id);
                })
                ->take(3)
                ->get();

            if ($relatedProjects->isEmpty()) {
                $relatedProjects = Project::with(['location', 'plotTypes'])
                    ->where('is_published', true)
                    ->where('id', '!=', $project->id)
                    ->take(3)
                    ->get();
            }
        }

        return view('frontend.projects.show', compact('project', 'relatedProjects'));
    }
}

