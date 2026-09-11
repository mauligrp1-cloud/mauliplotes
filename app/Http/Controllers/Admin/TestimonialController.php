<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display listing of testimonials
     */
    public function index(Request $request)
    {
        $testimonials = Testimonial::with('project')->latest()->paginate(15);
        $projects = Project::orderBy('name')->get();

        return view('admin.testimonials.index', compact('testimonials', 'projects'));
    }

    /**
     * Store new testimonial
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_image' => 'nullable|string|max:500',
            'project_id' => 'nullable|exists:projects,id',
            'short_quote' => 'required|string|max:500',
            'full_story' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'owner_since' => 'nullable|string|max:100',
            'featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['featured'] = $request->boolean('featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Customer testimonial added successfully.');
    }

    /**
     * Update testimonial
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_image' => 'nullable|string|max:500',
            'project_id' => 'nullable|exists:projects,id',
            'short_quote' => 'required|string|max:500',
            'full_story' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'owner_since' => 'nullable|string|max:100',
            'featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['featured'] = $request->boolean('featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Delete testimonial
     */
    public function destroy(Testimonial $testimonial)
    {
        $name = $testimonial->customer_name;
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', "Testimonial from '{$name}' deleted successfully.");
    }
}
