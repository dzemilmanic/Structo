<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Store a newly created testimonial in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Get paginated testimonials for AJAX loading.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTestimonials(Request $request)
    {
        $page = $request->query('page', 0);
        $perPage = 2; // Always display exactly 2 testimonials per page
        
        $testimonials = Testimonial::with('user')
            ->orderBy('created_at', 'desc')
            ->skip($page * $perPage)
            ->take($perPage)
            ->get();
            
        return response()->json([
            'testimonials' => $testimonials,
            'total' => Testimonial::count(),
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil(Testimonial::count() / $perPage)
        ]);
    }
    
    /**
     * Get paginated testimonials for the home page.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHomePageTestimonials()
    {
        return Testimonial::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}