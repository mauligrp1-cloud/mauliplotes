<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display media library grid or return JSON for media picker
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');
        $search = $request->query('search');

        $media = Media::with('uploadedBy')
            ->search($search)
            ->ofType($type)
            ->latest()
            ->paginate(24)
            ->withQueryString();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $media->items(),
                'pagination' => [
                    'current_page' => $media->currentPage(),
                    'last_page' => $media->lastPage(),
                    'total' => $media->total(),
                    'has_more' => $media->hasMorePages(),
                ],
            ]);
        }

        $totalCount = Media::count();
        $imageCount = Media::where('type', 'image')->count();
        $pdfCount = Media::where('type', 'pdf')->count();

        return view('admin.media.index', compact('media', 'type', 'search', 'totalCount', 'imageCount', 'pdfCount'));
    }

    /**
     * Store one or multiple uploaded media assets
     */
    public function store(Request $request)
    {
        // Support either single 'file' or multiple 'files' array (Max 200MB per file)
        $request->validate([
            'files' => 'nullable|array',
            'files.*' => 'required|file|mimes:jpeg,jpg,png,webp,svg,gif,pdf,mp4,mov,doc,docx|max:204800',
            'file' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg,gif,pdf,mp4,mov,doc,docx|max:204800',
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
        ], [
            'file.max' => 'The file exceeds the maximum allowed size (200MB).',
            'files.*.max' => 'One or more files exceed the maximum allowed size (200MB).',
            'file.mimes' => 'The file format is not supported. Please upload JPG, PNG, WebP, SVG, PDF, or MP4.',
            'files.*.mimes' => 'One or more files have an unsupported format.',
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            $uploadedFiles = $request->file('files');
        } elseif ($request->hasFile('file')) {
            $uploadedFiles = [$request->file('file')];
        }

        if (empty($uploadedFiles)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No files were uploaded.'], 422);
            }
            return back()->with('error', 'Please select at least one file to upload.');
        }

        $savedMedia = [];
        $folder = 'uploads/' . date('Y/m');

        foreach ($uploadedFiles as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Determine media type
            $type = 'other';
            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif ($extension === 'pdf' || $mimeType === 'application/pdf') {
                $type = 'pdf';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $type = 'video';
            } elseif (in_array($extension, ['doc', 'docx', 'txt', 'xls', 'xlsx'])) {
                $type = 'document';
            }

            // Generate safe, sanitized stored filename
            $baseName = pathinfo($originalName, PATHINFO_FILENAME);
            $safeSlug = Str::slug($baseName);
            if (empty($safeSlug)) {
                $safeSlug = 'file';
            }
            $storedFilename = $safeSlug . '-' . Str::random(8) . '.' . $extension;

            // Store file to public storage disk
            $path = $file->storeAs($folder, $storedFilename, 'public');

            // Determine dimensions if image
            $width = null;
            $height = null;
            if ($type === 'image' && in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $imageInfo = @getimagesize($file->getRealPath());
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                }
            }

            $media = Media::create([
                'original_filename' => $originalName,
                'stored_filename' => $storedFilename,
                'file_path' => $path,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'width' => $width,
                'height' => $height,
                'type' => $type,
                'title' => $request->input('title') ?? pathinfo($originalName, PATHINFO_FILENAME),
                'alt_text' => $request->input('alt_text') ?? pathinfo($originalName, PATHINFO_FILENAME),
                'uploaded_by' => auth()->id() ?? 1,
            ]);

            $savedMedia[] = $media;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => count($savedMedia) . ' file(s) uploaded successfully.',
                'media' => $savedMedia,
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', count($savedMedia) . ' file(s) uploaded successfully.');
    }

    /**
     * Update media asset metadata (title, alt_text)
     */
    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $media->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Media metadata updated successfully.',
                'media' => $media,
            ]);
        }

        return back()->with('success', 'Media metadata updated successfully.');
    }

    /**
     * Delete media asset from storage and database
     */
    public function destroy(Request $request, Media $media)
    {
        // Safe delete from storage disk if exists
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Media asset deleted successfully.',
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', 'Media asset deleted successfully.');
    }
}
