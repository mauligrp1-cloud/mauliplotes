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
        // Safe allowed business formats (Max 20MB per file) - SVG explicitly excluded to prevent Stored XSS
        $request->validate([
            'files' => 'nullable|array',
            'files.*' => 'required|file|mimes:jpeg,jpg,png,webp,gif,pdf,mp4,mov,doc,docx|max:20480',
            'file' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif,pdf,mp4,mov,doc,docx|max:20480',
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
        ], [
            'file.max' => 'The file exceeds the maximum allowed size (20MB).',
            'files.*.max' => 'One or more files exceed the maximum allowed size (20MB).',
            'file.mimes' => 'The file format is not supported. Please upload JPG, PNG, WebP, PDF, or MP4.',
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

        // Strictly block dangerous script and executable extensions
        $prohibitedExtensions = ['php', 'phar', 'phtml', 'html', 'htm', 'js', 'exe', 'sh', 'py', 'svg', 'bat', 'cmd', 'vbs'];

        foreach ($uploadedFiles as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            if (in_array($extension, $prohibitedExtensions)) {
                \Illuminate\Support\Facades\Log::warning('Security: Prohibited file extension upload attempt rejected.', [
                    'original_name' => $originalName,
                    'extension' => $extension,
                    'user_id' => auth()->id()
                ]);
                abort(422, 'Uploaded file type is not permitted for security reasons.');
            }

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

            // Process and optimize upload (Auto-compress 15-20MB images to crisp HD WebP in KB)
            $uploadResult = \App\Services\ImageOptimizer::optimizeAndStore($file, $folder);

            $media = Media::create([
                'original_filename' => $originalName,
                'stored_filename' => $uploadResult['stored_filename'],
                'file_path' => $uploadResult['file_path'],
                'mime_type' => $uploadResult['mime_type'],
                'file_size' => $uploadResult['file_size'],
                'width' => $uploadResult['width'],
                'height' => $uploadResult['height'],
                'type' => $uploadResult['type'],
                'title' => $request->input('title') ?? pathinfo($originalName, PATHINFO_FILENAME),
                'alt_text' => $request->input('alt_text') ?? pathinfo($originalName, PATHINFO_FILENAME),
                'uploaded_by' => auth()->id() ?? 1,
            ]);

            $savedMedia[] = $media;
        }

        \Illuminate\Support\Facades\Log::info('Security: Media files uploaded successfully.', [
            'count' => count($savedMedia),
            'uploaded_by' => auth()->id()
        ]);

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
        // Safe delete: prevent path traversal by ensuring file_path resides within uploads/ directory
        if (is_string($media->file_path) && str_starts_with($media->file_path, 'uploads/')) {
            if (Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
        }

        $media->delete();

        \Illuminate\Support\Facades\Log::info('Security: Media asset deleted.', [
            'media_id' => $media->id,
            'file_path' => $media->file_path,
            'deleted_by' => auth()->id()
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Media asset deleted successfully.',
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', 'Media asset deleted successfully.');
    }
}
