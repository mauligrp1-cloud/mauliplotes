<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display the full gallery management page with categories and items.
     */
    public function index()
    {
        $categories = GalleryCategory::with(['galleries.items' => function ($q) {
            $q->orderBy('sort_order');
        }])->get();

        // Flatten: each category gets its items directly
        $categoriesWithItems = $categories->map(function ($cat) {
            $items = $cat->galleries->flatMap(fn($g) => $g->items)->sortBy('sort_order')->values();
            $cat->allItems = $items;
            $cat->defaultGalleryId = $cat->galleries->first()?->id;
            return $cat;
        });

        $projects = Project::orderBy('name')->get();

        return view('admin.gallery.index', compact('categoriesWithItems', 'projects'));
    }

    /**
     * Store a new gallery category and auto-create a default Gallery record for it.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:gallery_categories,name',
        ]);

        $slug = Str::slug($request->name);
        $cat = GalleryCategory::create(['name' => $request->name, 'slug' => $slug]);

        // Always create a backing Gallery record so items can attach to it
        Gallery::create([
            'category_id' => $cat->id,
            'title'       => $request->name,
            'slug'        => $slug . '-' . $cat->id,
            'is_active'   => true,
            'sort_order'  => 0,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', "Category \"{$request->name}\" created.");
    }

    /**
     * Delete a category (cascades to galleries and gallery_items).
     */
    public function destroyCategory(GalleryCategory $category)
    {
        $name = $category->name;
        // Delete all child galleries first (which cascade to items)
        $category->galleries()->each(function ($g) {
            $g->items()->delete();
            $g->delete();
        });
        $category->delete();

        return redirect()->route('admin.gallery.index')->with('success', "Category \"{$name}\" deleted.");
    }

    /**
     * Store a single gallery image.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gallery_id' => 'required|exists:galleries,id',
            'title'      => 'nullable|string|max:255',
            'image'      => 'required|string|max:500',
            'project_id' => 'nullable|exists:projects,id',
            'caption'    => 'nullable|string|max:500',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? GalleryItem::where('gallery_id', $validated['gallery_id'])->max('sort_order') + 1;
        $validated['alt_text']   = $validated['alt_text'] ?? ($validated['title'] ?? 'Gallery Image');
        $validated['is_active']  = true;

        GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Photo added successfully.');
    }

    /**
     * Bulk store multiple images at once.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'gallery_id' => 'required|exists:galleries,id',
            'images'     => 'required|array|min:1',
            'images.*'   => 'required|string|max:500',
        ]);

        $galleryId = $request->gallery_id;
        $nextOrder = GalleryItem::where('gallery_id', $galleryId)->max('sort_order') + 1;

        foreach ($request->images as $imgUrl) {
            if (empty(trim($imgUrl))) continue;
            GalleryItem::create([
                'gallery_id' => $galleryId,
                'image'      => trim($imgUrl),
                'title'      => 'Gallery Photo',
                'alt_text'   => 'Gallery Photo',
                'sort_order' => $nextOrder++,
                'is_active'  => true,
            ]);
        }

        $count = count($request->images);
        return redirect()->route('admin.gallery.index')->with('success', "{$count} photos added successfully.");
    }

    /**
     * Update a gallery item (title, caption, alt_text, sort_order).
     */
    public function update(Request $request, GalleryItem $galleryItem)
    {
        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'caption'    => 'nullable|string|max:500',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $galleryItem->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Photo updated.');
    }

    /**
     * Toggle active/inactive status (AJAX).
     */
    public function toggleActive(GalleryItem $galleryItem)
    {
        $galleryItem->update(['is_active' => !$galleryItem->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $galleryItem->is_active,
        ]);
    }

    /**
     * Drag-drop reorder (AJAX) — receives ordered array of item IDs.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items'   => 'required|array',
            'items.*' => 'integer|exists:gallery_items,id',
        ]);

        foreach ($request->items as $index => $id) {
            GalleryItem::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Delete a gallery item.
     */
    public function destroy(GalleryItem $galleryItem)
    {
        $galleryItem->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Photo deleted.');
    }
}
