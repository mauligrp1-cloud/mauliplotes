<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:content.view')->only(['index', 'show']);
        $this->middleware('permission:content.edit')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display listing of team members
     */
    public function index()
    {
        $team = TeamMember::orderBy('sort_order')->paginate(15);
        return view('admin.team.index', compact('team'));
    }

    /**
     * Store new team member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'photo' => 'nullable|string|max:500',
            'short_bio' => 'nullable|string|max:500',
            'full_bio' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        TeamMember::create($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member added successfully.');
    }

    /**
     * Update team member
     */
    public function update(Request $request, TeamMember $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'photo' => 'nullable|string|max:500',
            'short_bio' => 'nullable|string|max:500',
            'full_bio' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $team->update($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    /**
     * Delete team member
     */
    public function destroy(TeamMember $team)
    {
        $name = $team->name;
        $team->delete();

        return redirect()->route('admin.team.index')->with('success', "Team member '{$name}' deleted successfully.");
    }
}
