<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AmenityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:projects.view')->only(['index']);
        $this->middleware('permission:projects.edit')->only(['store', 'update']);
        $this->middleware('permission:projects.delete')->only(['destroy']);
    }

    /**
     * Display a listing of amenities
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Amenity::withCount('projects')->latest();

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
        }

        $amenities = $query->paginate(15)->withQueryString();

        return view('admin.amenities.index', compact('amenities', 'search'));
    }

    /**
     * Store a newly created amenity
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name',
            'slug' => 'nullable|string|max:255|unique:amenities,slug',
            'icon' => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        Amenity::create($validated);

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity created successfully.');
    }

    /**
     * Update the specified amenity
     */
    public function update(Request $request, Amenity $amenity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name,' . $amenity->id,
            'slug' => 'required|string|max:255|unique:amenities,slug,' . $amenity->id,
            'icon' => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $amenity->update($validated);

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity updated successfully.');
    }

    /**
     * Remove the specified amenity
     */
    public function destroy(Amenity $amenity)
    {
        $amenity->projects()->detach();
        $name = $amenity->name;
        $amenity->delete();

        return redirect()->route('admin.amenities.index')->with('success', "Amenity '{$name}' deleted successfully.");
    }
}
