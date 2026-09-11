<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    /**
     * Display listing of 301/302 redirects
     */
    public function index()
    {
        $redirects = Redirect::latest()->paginate(20);
        return view('admin.seo.redirects', compact('redirects'));
    }

    /**
     * Store new redirect
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'old_url' => 'required|string|max:500|unique:redirects,old_url',
            'new_url' => 'required|string|max:500',
            'redirect_type' => 'required|in:301,302',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['old_url'] = '/' . ltrim(trim($validated['old_url']), '/');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['hits'] = 0;

        Redirect::create($validated);

        return redirect()->route('admin.seo.redirects.index')->with('success', 'Redirect rule added successfully.');
    }

    /**
     * Update redirect
     */
    public function update(Request $request, Redirect $redirect)
    {
        $validated = $request->validate([
            'old_url' => 'required|string|max:500|unique:redirects,old_url,' . $redirect->id,
            'new_url' => 'required|string|max:500',
            'redirect_type' => 'required|in:301,302',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['old_url'] = '/' . ltrim(trim($validated['old_url']), '/');
        $validated['is_active'] = $request->boolean('is_active', true);

        $redirect->update($validated);

        return redirect()->route('admin.seo.redirects.index')->with('success', 'Redirect rule updated successfully.');
    }

    /**
     * Delete redirect
     */
    public function destroy(Redirect $redirect)
    {
        $redirect->delete();
        return redirect()->route('admin.seo.redirects.index')->with('success', 'Redirect rule deleted.');
    }
}
