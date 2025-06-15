<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfiRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfiRequestController extends Controller
{
    public function store(Request $request)
    {
        // Check if user already has a pending request
        $existingRequest = ProfiRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a pending professional request.');
        }

        // Check if user is already a professional
        if (Auth::user()->role === 'profi') {
            return redirect()->back()->with('error', 'You are already a professional user.');
        }

        $request->validate([
            'specialization' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profi_proofs', 's3');
            Storage::disk('s3')->setVisibility($path, 'public');
        }

        ProfiRequest::create([
            'user_id' => Auth::id(),
            'specialization' => $request->specialization,
            'image' => $path,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Your professional request has been submitted successfully and is pending review.');
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
        $profiRequest = ProfiRequest::findOrFail($id);
        
        // Update request status
        $profiRequest->update(['status' => 'accepted']);

        // Update user role and specialization
        $user = $profiRequest->user;
        $user->update([
            'role' => 'profi',
            'specialization' => $profiRequest->specialization
        ]);

        return back()->with('success', 'User has been successfully promoted to professional status.');
    }

    public function reject($id)
    {
        $profiRequest = ProfiRequest::findOrFail($id);
        
        // Delete the image from S3 if it exists
        if ($profiRequest->image) {
            Storage::disk('s3')->delete($profiRequest->image);
        }
        
        // Update status to rejected (or delete the request)
        $profiRequest->update(['status' => 'rejected']);
        
        // Optionally delete the request entirely
        $profiRequest->delete();

        return back()->with('success', 'Professional request has been rejected and removed.');
    }
}