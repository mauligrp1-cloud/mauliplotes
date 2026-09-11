<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Maximum dimension (width or height) in pixels for HD clarity
     */
    protected const MAX_DIMENSION = 1920;

    /**
     * WebP output quality (0-100) — 82 gives indistinguishable visual quality with 90%+ compression
     */
    protected const WEBP_QUALITY = 82;

    /**
     * Optimize and store an uploaded image.
     * Compresses large images (15MB - 20MB) down to KB in crisp HD WebP format.
     * Falls back to standard storage if not a convertible image or if GD fails.
     *
     * @param UploadedFile $file
     * @param string $folder Relative folder path, e.g. 'uploads/2026/09'
     * @return array
     */
    public static function optimizeAndStore(UploadedFile $file, string $folder): array
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();

        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $safeSlug = Str::slug($baseName);
        if (empty($safeSlug)) {
            $safeSlug = 'image';
        }

        $isConvertibleImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'bmp'])
            && function_exists('imagewebp')
            && function_exists('imagecreatefromstring');

        if ($isConvertibleImage) {
            try {
                $rawContents = file_get_contents($file->getRealPath());
                if ($rawContents !== false) {
                    $sourceImage = @imagecreatefromstring($rawContents);

                    if ($sourceImage !== false) {
                        // Fix phone camera EXIF orientation if JPEG and exif extension available
                        if (function_exists('exif_read_data') && in_array($extension, ['jpg', 'jpeg'])) {
                            try {
                                $exif = @exif_read_data($file->getRealPath());
                                if (!empty($exif['Orientation'])) {
                                    switch ($exif['Orientation']) {
                                        case 3:
                                            $sourceImage = imagerotate($sourceImage, 180, 0);
                                            break;
                                        case 6:
                                            $sourceImage = imagerotate($sourceImage, -90, 0);
                                            break;
                                        case 8:
                                            $sourceImage = imagerotate($sourceImage, 90, 0);
                                            break;
                                    }
                                }
                            } catch (\Throwable $e) {
                                // Ignore exif read errors
                            }
                        }

                        $origWidth = imagesx($sourceImage);
                        $origHeight = imagesy($sourceImage);

                        // Calculate new dimensions (max 1920px width or height, preserving aspect ratio)
                        $targetWidth = $origWidth;
                        $targetHeight = $origHeight;

                        if ($origWidth > self::MAX_DIMENSION || $origHeight > self::MAX_DIMENSION) {
                            if ($origWidth >= $origHeight) {
                                $targetWidth = self::MAX_DIMENSION;
                                $targetHeight = (int) round(($origHeight / $origWidth) * self::MAX_DIMENSION);
                            } else {
                                $targetHeight = self::MAX_DIMENSION;
                                $targetWidth = (int) round(($origWidth / $origHeight) * self::MAX_DIMENSION);
                            }
                        }

                        // Create truecolor canvas
                        $optimizedImage = imagecreatetruecolor($targetWidth, $targetHeight);

                        // Maintain transparency for PNG / WebP
                        imagealphablending($optimizedImage, false);
                        imagesavealpha($optimizedImage, true);
                        $transparent = imagecolorallocatealpha($optimizedImage, 255, 255, 255, 127);
                        imagefilledrectangle($optimizedImage, 0, 0, $targetWidth, $targetHeight, $transparent);

                        // Resample high quality
                        imagecopyresampled(
                            $optimizedImage,
                            $sourceImage,
                            0, 0, 0, 0,
                            $targetWidth,
                            $targetHeight,
                            $origWidth,
                            $origHeight
                        );

                        // Capture WebP binary stream
                        ob_start();
                        imagewebp($optimizedImage, null, self::WEBP_QUALITY);
                        $webpBinary = ob_get_clean();

                        // Clean up GD resources
                        imagedestroy($sourceImage);
                        imagedestroy($optimizedImage);

                        if (!empty($webpBinary)) {
                            $storedFilename = $safeSlug . '-' . Str::random(8) . '.webp';
                            $path = $folder . '/' . $storedFilename;

                            // Store via Laravel Storage disk
                            Storage::disk('public')->put($path, $webpBinary);

                            $newSize = strlen($webpBinary);

                            return [
                                'stored_filename' => $storedFilename,
                                'file_path' => $path,
                                'mime_type' => 'image/webp',
                                'file_size' => $newSize,
                                'width' => $targetWidth,
                                'height' => $targetHeight,
                                'type' => 'image'
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Image optimization fallback: ' . $e->getMessage());
            }
        }

        // Standard fallback for PDFs, Videos, or non-convertible files
        $storedFilename = $safeSlug . '-' . Str::random(8) . '.' . $extension;
        $path = $file->storeAs($folder, $storedFilename, 'public');

        $width = null;
        $height = null;
        $type = 'other';

        if (str_starts_with($mimeType, 'image/')) {
            $type = 'image';
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
            }
        } elseif ($extension === 'pdf' || $mimeType === 'application/pdf') {
            $type = 'pdf';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $type = 'video';
        } elseif (in_array($extension, ['doc', 'docx', 'txt', 'xls', 'xlsx'])) {
            $type = 'document';
        }

        return [
            'stored_filename' => $storedFilename,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'width' => $width,
            'height' => $height,
            'type' => $type
        ];
    }
}
