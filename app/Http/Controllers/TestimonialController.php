<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:1000',
        'title' => 'nullable|string|max:255',
    ]);

    Testimonial::create([
        'user_id' => Auth::id(),
        'title' => $request->title,
        'content' => $request->content,
    ]);

    return redirect()->back()->with('success', 'Thank you for your feedback!');
}
}
