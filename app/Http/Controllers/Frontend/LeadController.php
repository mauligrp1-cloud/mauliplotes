<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\SiteVisit;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Store new customer inquiry or site visit booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'project_id' => 'nullable',
            'project_interest' => 'nullable|string|max:255',
            'location_interest' => 'nullable|string|max:255',
            'lead_type' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:100',
            'requirement_type' => 'nullable|string|max:100',
            'site_visit_date' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:1000',
        ]);

        $notes = [];
        if (!empty($validated['project_interest'])) {
            $notes[] = 'Project: ' . $validated['project_interest'];
        }
        if (!empty($validated['location_interest'])) {
            $notes[] = 'Location: ' . $validated['location_interest'];
        }
        if (!empty($validated['site_visit_date'])) {
            $notes[] = 'Site Visit: ' . $validated['site_visit_date'];
        }
        if (!empty($validated['message'])) {
            $notes[] = $validated['message'];
        }

        $lead = Lead::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'project_id' => !empty($validated['project_id']) && is_numeric($validated['project_id']) ? $validated['project_id'] : null,
            'source' => $validated['source'] ?? 'Homepage Lead Form',
            'property_type' => $validated['requirement_type'] ?? 'Residential Plot',
            'status' => 'new',
            'notes' => implode(' | ', $notes) ?: null,
        ]);

        // If site visit date specified, record in site_visits table
        if (!empty($validated['site_visit_date'])) {
            SiteVisit::create([
                'customer_name' => $lead->name,
                'customer_phone' => $lead->phone,
                'customer_email' => $lead->email,
                'lead_id' => $lead->id,
                'project_id' => $lead->project_id,
                'visit_date' => now()->addDays(2)->format('Y-m-d'),
                'visit_time' => '11:00:00',
                'status' => 'pending',
                'message' => 'Booked via Website Form for ' . $validated['site_visit_date'],
                'internal_notes' => 'Source: ' . ($validated['source'] ?? 'Website Form'),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Our property advisor will get in touch with you shortly with the brochure & pricing.'
            ]);
        }

        return back()->with('success', 'Thank you! Your enquiry has been received. Our property advisor will connect with you shortly.');
    }
}
