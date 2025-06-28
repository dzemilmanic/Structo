<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfiRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'specialization', 
        'files', 
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // Don't cast files to array here, handle it manually
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for pending requests
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for accepted requests
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    // Scope for rejected requests
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Get files with full S3 URLs
    public function getFilesWithUrlsAttribute()
    {
        if (!$this->files) {
            return [];
        }

        // Decode JSON if it's a string
        $filesData = is_string($this->files) ? json_decode($this->files, true) : $this->files;
        
        if (!is_array($filesData)) {
            return [];
        }

        return collect($filesData)->map(function ($file) {
            // Ensure $file is an array
            if (!is_array($file)) {
                return null;
            }

            // Generate proper S3 URL
            $s3Url = $this->generateS3Url($file['path'] ?? '');

            return [
                'path' => $file['path'] ?? '',
                'original_name' => $file['original_name'] ?? 'Unknown',
                'size' => $file['size'] ?? 0,
                'mime_type' => $file['mime_type'] ?? 'application/octet-stream',
                'url' => $s3Url,
                'formatted_size' => $this->formatFileSize($file['size'] ?? 0),
                'icon_class' => $this->getFileIconClass($file['mime_type'] ?? '')
            ];
        })->filter()->toArray(); // Remove null values
    }

    // Generate proper S3 URL
    private function generateS3Url($path)
    {
        if (!$path) {
            return '';
        }

        // If it's already a full URL, return it
        if (str_contains($path, 'amazonaws.com') || str_contains($path, 'http')) {
            return $path;
        }

        // Get S3 configuration
        $bucket = config('filesystems.disks.s3.bucket');
        $region = config('filesystems.disks.s3.region');
        
        if (!$bucket || !$region) {
            // Fallback to Laravel's Storage facade
            try {
                return \Storage::disk('s3')->url($path);
            } catch (\Exception $e) {
                \Log::error('Failed to generate S3 URL: ' . $e->getMessage(), ['path' => $path]);
                return '';
            }
        }

        // Construct direct S3 URL
        $cleanPath = ltrim($path, '/');
        return "https://{$bucket}.s3.{$region}.amazonaws.com/{$cleanPath}";
    }

    // Helper method to format file size
    private function formatFileSize($bytes)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    // Helper method to get file icon class
    private function getFileIconClass($mimeType)
    {
        if (str_contains($mimeType, 'pdf')) return 'file-pdf';
        if (str_contains($mimeType, 'word') || str_contains($mimeType, 'document')) return 'file-word';
        if (str_contains($mimeType, 'image')) return 'file-image';
        return 'file-generic';
    }

    // Get decoded files array
    public function getDecodedFilesAttribute()
    {
        if (!$this->files) {
            return [];
        }

        $filesData = is_string($this->files) ? json_decode($this->files, true) : $this->files;
        
        return is_array($filesData) ? $filesData : [];
    }
}