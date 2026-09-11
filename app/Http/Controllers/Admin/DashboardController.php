<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Lead;
use App\Models\Location;
use App\Models\Project;
use App\Models\SiteVisit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display real-estate admin dashboard overview
     */
    public function index()
    {
        // 1. Dashboard Statistics
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'upcoming_projects' => Project::where('status', 'upcoming')->count(),
            'total_locations' => Location::count(),
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'total_site_visits' => SiteVisit::count(),
            'upcoming_site_visits_count' => SiteVisit::whereIn('status', ['pending', 'confirmed'])
                ->whereDate('visit_date', '>=', now()->toDateString())
                ->count(),
            'published_blogs' => BlogPost::where('status', 'published')->count(),
        ];

        // 2. Recent Leads (with project and location eager loaded)
        $recentLeads = Lead::with(['project', 'location'])
            ->latest()
            ->take(6)
            ->get();

        // 3. Upcoming Site Visits (with project and assigned staff eager loaded)
        $upcomingSiteVisits = SiteVisit::with(['project', 'assignedTo', 'lead'])
            ->whereDate('visit_date', '>=', now()->subDays(1)->toDateString())
            ->orderBy('visit_date')
            ->orderBy('visit_time')
            ->take(5)
            ->get();

        // 4. Recent Projects (with location eager loaded)
        $recentProjects = Project::with('location')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentLeads', 'upcomingSiteVisits', 'recentProjects'));
    }
}
