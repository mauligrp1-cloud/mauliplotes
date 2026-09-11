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
        // Anti-Spam Honeypot check: If bot fills hidden honeypot field, drop silently
        if ($request->filled('website_hp')) {
            \Illuminate\Support\Facades\Log::info('Security: Bot submission caught by honeypot.');
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you! Our property advisor will get in touch with you shortly.'
                ]);
            }
            return back()->with('lead_success', 'Thank you! Our advisor will contact you shortly.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+\s\-()]{7,20}$/'],
            'email' => 'nullable|email|max:150',
            'project_id' => 'nullable',
            'project_interest' => 'nullable|string|max:255',
            'location_interest' => 'nullable|string|max:255',
            'lead_type' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:100',
            'requirement_type' => 'nullable|string|max:100',
            'site_visit_date' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:1000',
        ], [
            'phone.regex' => 'Please enter a valid contact phone number.',
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
