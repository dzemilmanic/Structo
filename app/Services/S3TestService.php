<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class S3TestService
{
    /**
     * Test S3 connection and permissions
     */
    public function testS3Connection(): array
    {
        $results = [];
        
        try {
            // Test 1: Basic connection
            $disk = Storage::disk('profile_photos');
            $results['connection'] = 'OK';
            
            // Test 2: List bucket contents (to test read permissions)
            try {
                $files = $disk->files();
                $results['read_permission'] = 'OK - Found ' . count($files) . ' files';
            } catch (\Exception $e) {
                $results['read_permission'] = 'FAILED: ' . $e->getMessage();
            }
            
            // Test 3: Write test file
            try {
                $testContent = 'Test file created at ' . now();
                $testPath = 'test-' . time() . '.txt';
                $disk->put($testPath, $testContent);
                $results['write_permission'] = 'OK';
                
                // Test 4: Read the test file back
                $readContent = $disk->get($testPath);
                if ($readContent === $testContent) {
                    $results['read_write_cycle'] = 'OK';
                } else {
                    $results['read_write_cycle'] = 'FAILED: Content mismatch';
                }
                
                // Test 5: Get URL
                try {
                    $url = $disk->url($testPath);
                    $results['url_generation'] = 'OK: ' . $url;
                } catch (\Exception $e) {
                    $results['url_generation'] = 'FAILED: ' . $e->getMessage();
                }
                
                // Clean up test file
                $disk->delete($testPath);
                $results['cleanup'] = 'OK';
                
            } catch (\Exception $e) {
                $results['write_permission'] = 'FAILED: ' . $e->getMessage();
            }
            
        } catch (\Exception $e) {
            $results['connection'] = 'FAILED: ' . $e->getMessage();
        }
        
        return $results;
    }
    
    /**
     * Get S3 configuration info
     */
    public function getS3Config(): array
    {
        return [
            'bucket' => env('AWS_BUCKET'),
            'region' => env('AWS_DEFAULT_REGION'),
            'access_key_id' => env('AWS_ACCESS_KEY_ID') ? 'Set (***' . substr(env('AWS_ACCESS_KEY_ID'), -4) . ')' : 'Not set',
            'secret_access_key' => env('AWS_SECRET_ACCESS_KEY') ? 'Set (***' . substr(env('AWS_SECRET_ACCESS_KEY'), -4) . ')' : 'Not set',
            'url' => env('AWS_URL', 'Default'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false) ? 'true' : 'false',
        ];
    }
}