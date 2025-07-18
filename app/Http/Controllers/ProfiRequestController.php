<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfiRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfiRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Check if user already has a pending request
            $existingRequest = ProfiRequest::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You already have a pending professional request.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'You already have a pending professional request.');
            }

            // Check if user is already a professional
            if (Auth::user()->role === 'profi') {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are already a professional user.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'You are already a professional user.');
            }

            // Updated validation with new file limits and custom validation
            $request->validate([
                'specialization' => 'required|string|max:255',
                'files' => 'nullable|array|max:2', // Maximum 2 files
                'files.*' => [
                    'file',
                    'mimes:pdf,doc,docx,jpg,jpeg,png,gif,webp',
                    function ($attribute, $value, $fail) {
                        // Get file type to determine size limit
                        $mimeType = $value->getMimeType();
                        $documentTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        
                        if (in_array($mimeType, $documentTypes)) {
                            // 1MB limit for documents
                            if ($value->getSize() > 1024 * 1024) {
                                $fail('Document files must be no larger than 1MB.');
                            }
                        } elseif (in_array($mimeType, $imageTypes)) {
                            // 5MB limit for images
                            if ($value->getSize() > 5 * 1024 * 1024) {
                                $fail('Image files must be no larger than 5MB.');
                            }
                        } else {
                            $fail('Invalid file type. Only PDF, Word documents, and images are allowed.');
                        }
                    }
                ]
            ]);

            $uploadedFiles = [];
            
            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    try {
                        // Generate unique filename
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        
                        // Store file to S3
                        $path = Storage::disk('s3')->putFileAs('profi_documents', $file, $filename);
                        
                        // Make file public
                        Storage::disk('s3')->setVisibility($path, 'public');
                        
                        $uploadedFiles[] = [
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime_type' => $file->getMimeType()
                        ];
                        
                        Log::info('File uploaded successfully to S3', [
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName()
                        ]);
                        
                    } catch (\Exception $e) {
                        Log::error('S3 Upload Error: ' . $e->getMessage(), [
                            'file' => $file->getClientOriginalName()
                        ]);
                        
                        // Clean up any uploaded files if one fails
                        foreach ($uploadedFiles as $uploadedFile) {
                            Storage::disk('s3')->delete($uploadedFile['path']);
                        }
                        
                        if ($request->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Failed to upload files. Please try again.'
                            ], 500);
                        }
                        return redirect()->back()->with('error', 'Failed to upload files. Please try again.');
                    }
                }
            }

            // Create the professional request
            ProfiRequest::create([
                'user_id' => Auth::id(),
                'specialization' => $request->input('specialization'),
                'files' => json_encode($uploadedFiles), // Store file info as JSON
                'status' => 'pending'
            ]);

            $successMessage = 'Your professional request has been submitted successfully and is pending review.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ], 200);
            }
            
            return redirect()->back()->with('success', $successMessage);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->validator)->withInput();
            
        } catch (\Exception $e) {
            // If database save fails, delete uploaded files
            foreach ($uploadedFiles ?? [] as $uploadedFile) {
                Storage::disk('s3')->delete($uploadedFile['path']);
            }
            
            Log::error('Professional Request Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to submit request. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to submit request. Please try again.');
        }
    }

    public function index()
    {
        $requests = ProfiRequest::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(4);
            
        return view('admin.profi_requests.index', compact('requests'));
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $profiRequest = ProfiRequest::findOrFail($id);
            
            // Check if request is still pending
            if ($profiRequest->status !== 'pending') {
                return back()->with('error', 'This request has already been processed.');
            }
            
            // Update request status
            $profiRequest->update(['status' => 'accepted']);

            // Update user role and specialization
            $user = $profiRequest->user;
            $user->update([
                'role' => 'profi',
                'specialization' => $profiRequest->specialization
            ]);

            DB::commit();
            
            Log::info('Professional request approved', [
                'request_id' => $id,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'specialization' => $profiRequest->specialization
            ]);

            return back()->with('success', $user->name . ' ' . ($user->lastname ?? '') . ' has been successfully promoted to professional status.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving professional request: ' . $e->getMessage(), [
                'request_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to approve the request. Please try again.');
        }
    }

    public function reject($id)
    {
        try {
            DB::beginTransaction();
            
            $profiRequest = ProfiRequest::findOrFail($id);
            
            // Check if request is still pending
            if ($profiRequest->status !== 'pending') {
                return back()->with('error', 'This request has already been processed.');
            }
            
            $userName = $profiRequest->user->name . ' ' . ($profiRequest->user->lastname ?? '');
            
            // Delete uploaded files from S3
            if ($profiRequest->files) {
                $files = json_decode($profiRequest->files, true);
                if (is_array($files)) {
                    foreach ($files as $file) {
                        try {
                            Storage::disk('s3')->delete($file['path']);
                            Log::info('S3 file deleted successfully', ['file' => $file['path']]);
                        } catch (\Exception $e) {
                            Log::error('Failed to delete S3 file: ' . $e->getMessage(), [
                                'file' => $file['path']
                            ]);
                        }
                    }
                }
            }
            
            // Delete the request entirely
            $profiRequest->delete();
            
            DB::commit();
            
            Log::info('Professional request rejected and deleted', [
                'request_id' => $id,
                'user_name' => $userName
            ]);

            return back()->with('success', $userName . '\'s professional request has been rejected and removed.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting professional request: ' . $e->getMessage(), [
                'request_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to reject the request. Please try again.');
        }
    }
}