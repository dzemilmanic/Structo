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

        $request->validate([
            'specialization' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Store file to S3
                $path = Storage::disk('s3')->putFileAs('profi_proofs', $file, $filename);
                
                // Make file public
                Storage::disk('s3')->setVisibility($path, 'public');
                
                // Log success
                Log::info('File uploaded successfully to S3', ['path' => $path]);
                
            } catch (\Exception $e) {
                Log::error('S3 Upload Error: ' . $e->getMessage());
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to upload image. Please try again.'
                    ], 500);
                }
                return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
            }
        }

        try {
            ProfiRequest::create([
                'user_id' => Auth::id(),
                'specialization' => $request->specialization,
                'image' => $path,
                'status' => 'pending'
            ]);

            $successMessage = 'Your professional request has been submitted successfully and is pending review.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }
            
            return redirect()->back()->with('success', $successMessage);
            
        } catch (\Exception $e) {
            // If database save fails, delete uploaded file
            if ($path) {
                Storage::disk('s3')->delete($path);
            }
            
            Log::error('Database Error: ' . $e->getMessage());
            
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
            ->get();
            
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
            
            // Delete the image from S3 if it exists
            if ($profiRequest->image) {
                try {
                    Storage::disk('s3')->delete($profiRequest->image);
                    Log::info('S3 file deleted successfully', ['file' => $profiRequest->image]);
                } catch (\Exception $e) {
                    Log::error('Failed to delete S3 file: ' . $e->getMessage(), [
                        'file' => $profiRequest->image
                    ]);
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