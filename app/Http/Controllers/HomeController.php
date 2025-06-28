<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Get active service categories ordered by sort_order
            $serviceCategories = ServiceCategory::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            // Get testimonials with user information
            $testimonials = Testimonial::with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            // Ensure we always have collections, even if empty
            if ($serviceCategories === null) {
                $serviceCategories = collect([]);
            }
            
            if ($testimonials === null) {
                $testimonials = collect([]);
            }

            return view('home', compact('serviceCategories', 'testimonials'));
            
        } catch (\Exception $e) {
            // If there's any error, provide empty collections
            $serviceCategories = collect([]);
            $testimonials = collect([]);
            
            \Log::error('Error in HomeController: ' . $e->getMessage());
            
            return view('home', compact('serviceCategories', 'testimonials'));
        }
    }
}