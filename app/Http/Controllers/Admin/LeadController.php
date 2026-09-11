<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:leads.view')->only(['index', 'show']);
        $this->middleware('permission:leads.edit')->only(['edit', 'update', 'addNote']);
        $this->middleware('permission:leads.delete')->only(['destroy']);
        $this->middleware('permission:leads.export')->only(['exportCsv']);
    }

    /**
     * Display listing of CRM leads with filters
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $projectId = $request->query('project_id');
        $source = $request->query('source');

        $query = Lead::with(['project', 'assignedTo', 'notes'])->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($projectId)) {
            $query->where('project_id', $projectId);
        }

        if (!empty($source)) {
            $query->where('source', $source);
        }

        $leads = $query->paginate(20)->withQueryString();
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // Pipeline stage counts
        $counts = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'site_visit' => Lead::where('status', 'site_visit_scheduled')->count(),
            'negotiation' => Lead::where('status', 'negotiation')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
        ];

        return view('admin.leads.index', compact('leads', 'projects', 'users', 'search', 'status', 'projectId', 'source', 'counts'));
    }

    /**
     * Display single lead detail with notes history
     */
    public function show(Lead $lead)
    {
        $lead->load(['project', 'assignedTo', 'notes.author', 'siteVisits']);
        $users = User::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('admin.leads.show', compact('lead', 'users', 'projects'));
    }

    /**
     * Update lead status and assignment
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,site_visit_scheduled,negotiation,converted,dropped',
            'assigned_to' => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $lead->update($validated);

        return redirect()->back()->with('success', 'Lead updated successfully.');
    }

    /**
     * Add note to lead timeline
     */
    public function addNote(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        LeadNote::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'note' => $validated['note'],
        ]);

        return redirect()->back()->with('success', 'Note added to lead activity log.');
    }

    /**
     * Export leads as CSV
     */
    public function exportCsv()
    {
        $leads = Lead::with('project')->latest()->get();
        $filename = "mauli_leads_" . date('Y_m_d_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Customer Name', 'Phone', 'Email', 'Project', 'Source', 'Status', 'Created Date'];

        $callback = function() use ($leads, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->name,
                    $lead->phone,
                    $lead->email,
                    $lead->project->name ?? 'General',
                    $lead->source,
                    $lead->status,
                    $lead->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete lead
     */
    public function destroy(Lead $lead)
    {
        $name = $lead->name;
        $lead->notes()->delete();
        $lead->siteVisits()->delete();
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', "Lead '{$name}' deleted successfully.");
    }
}
