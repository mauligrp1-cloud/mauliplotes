<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SiteVisit;
use App\Models\User;
use Illuminate\Http\Request;

class SiteVisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:leads.view')->only(['index', 'show']);
        $this->middleware('permission:leads.edit')->only(['create', 'store', 'edit', 'update']);
        $this->middleware('permission:leads.delete')->only(['destroy']);
    }

    /**
     * Display listing of booked site visits
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = SiteVisit::with(['project', 'assignedTo', 'lead'])->latest('visit_date');

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        $visits = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('admin.site-visits.index', compact('visits', 'users', 'projects', 'status'));
    }

    /**
     * Update site visit details and status
     */
    public function update(Request $request, SiteVisit $site_visit)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,rescheduled,completed,cancelled',
            'assigned_to' => 'nullable|exists:users,id',
            'visit_date' => 'required|date',
            'visit_time' => 'nullable|string',
            'internal_notes' => 'nullable|string|max:1000',
        ]);

        $site_visit->update($validated);

        return redirect()->back()->with('success', 'Site visit schedule updated successfully.');
    }

    /**
     * Delete site visit
     */
    public function destroy(SiteVisit $site_visit)
    {
        $site_visit->delete();
        return redirect()->route('admin.site-visits.index')->with('success', 'Site visit record removed.');
    }
}
