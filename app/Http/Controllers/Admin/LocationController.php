<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:locations.view')->only(['index', 'show']);
        $this->middleware('permission:locations.create')->only(['create', 'store']);
        $this->middleware('permission:locations.edit')->only(['edit', 'update']);
        $this->middleware('permission:locations.delete')->only(['destroy']);
    }

    /**
     * Display a listing of strategic locations / growth corridors
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Location::withCount('projects')->latest();

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
        }

        $locations = $query->paginate(15)->withQueryString();

        return view('admin.locations.index', compact('locations', 'search'));
    }

    /**
     * Show form to create location
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store new location
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
            'slug' => 'nullable|string|max:255|unique:locations,slug',
            'short_description' => 'nullable|string|max:1000',
            'detailed_description' => 'nullable|string',
            'why_invest_here' => 'nullable|string',
            'connectivity' => 'nullable|string',
            'infrastructure' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'hero_image' => 'nullable|string|max:500',
            'mobile_hero' => 'nullable|string|max:500',
            'featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'canonical_url' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['featured'] = $request->boolean('featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Location::create($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Strategic location added successfully.');
    }

    /**
     * Show form to edit location
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update location
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
            'slug' => 'required|string|max:255|unique:locations,slug,' . $location->id,
            'short_description' => 'nullable|string|max:1000',
            'detailed_description' => 'nullable|string',
            'why_invest_here' => 'nullable|string',
            'connectivity' => 'nullable|string',
            'infrastructure' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'hero_image' => 'nullable|string|max:500',
            'mobile_hero' => 'nullable|string|max:500',
            'featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'canonical_url' => 'nullable|string|max:500',
        ]);

        $validated['featured'] = $request->boolean('featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        $location->update($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    /**
     * Delete location
     */
    public function destroy(Location $location)
    {
        $name = $location->name;
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', "Location '{$name}' deleted successfully.");
    }
}
