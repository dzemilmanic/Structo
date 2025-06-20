<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class PhotoService
{
    /**
     * Upload photo to S3 and return the URL
     */
    public function uploadProfilePhoto(UploadedFile $file, $userId = null): string
    {
        // Generate unique filename
        $filename = $this->generateUniqueFilename($file, $userId);
        
        // Store on S3
        $path = Storage::disk('profile_photos')->putFileAs('', $file, $filename);
        
        if (!$path) {
            throw new \Exception('Failed to upload photo to S3');
        }
        
        // Return the full S3 URL
        return Storage::disk('profile_photos')->url($path);
    }
    
    /**
     * Delete photo from S3
     */
    public function deleteProfilePhoto(string $photoUrl): bool
    {
        try {
            $key = $this->extractKeyFromUrl($photoUrl);
            if ($key) {
                return Storage::disk('profile_photos')->delete($key);
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to delete photo from S3: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate unique filename for photo
     */
    private function generateUniqueFilename(UploadedFile $file, $userId = null): string
    {
        $extension = $file->getClientOriginalExtension();
        $uuid = Str::uuid();
        
        if ($userId) {
            return "user_{$userId}_{$uuid}.{$extension}";
        }
        
        return "{$uuid}.{$extension}";
    }
    
    /**
     * Extract S3 key from full URL
     */
    private function extractKeyFromUrl(string $url): ?string
    {
        if (!$url) {
            return null;
        }
        
        // If it's a full S3 URL, extract the key
        if (str_contains($url, 'amazonaws.com')) {
            $parts = parse_url($url);
            $path = ltrim($parts['path'], '/');
            
            // Remove bucket name from path if present
            $bucketName = env('AWS_BUCKET');
            if (str_starts_with($path, $bucketName . '/')) {
                $path = substr($path, strlen($bucketName) + 1);
            }
            
            // Remove structo-slike prefix if present
            if (str_starts_with($path, 'structo-slike/')) {
                $path = substr($path, strlen('structo-slike/'));
            }
            
            return $path;
        }
        
        // If it's just a filename, return as is
        return basename($url);
    }
    
    /**
     * Validate photo file
     */
    public function validatePhoto(UploadedFile $file): array
    {
        $errors = [];
        
        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            $errors[] = 'Invalid file type. Only JPEG, PNG, JPG, and GIF are allowed.';
        }
        
        // Check file size (2MB max)
        $maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if ($file->getSize() > $maxSize) {
            $errors[] = 'File size must be less than 2MB.';
        }
        
        // Check image dimensions (optional)
        if ($file->getMimeType() && str_starts_with($file->getMimeType(), 'image/')) {
            $dimensions = getimagesize($file->getPathname());
            if ($dimensions) {
                $width = $dimensions[0];
                $height = $dimensions[1];
                
                // Max dimensions: 2000x2000px
                if ($width > 2000 || $height > 2000) {
                    $errors[] = 'Image dimensions must not exceed 2000x2000 pixels.';
                }
                
                // Min dimensions: 100x100px
                if ($width < 100 || $height < 100) {
                    $errors[] = 'Image dimensions must be at least 100x100 pixels.';
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Get optimized image URL (if using CDN)
     */
    public function getOptimizedImageUrl(string $photoUrl, int $width = null, int $height = null): string
    {
        // If using CloudFront or other CDN, you can add optimization parameters here
        // For now, return the original URL
        return $photoUrl;
    }
}