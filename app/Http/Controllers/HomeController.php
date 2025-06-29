<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all testimonials
        $testimonials = Testimonial::with('user')->latest()->get();
        
        // Calculate the number of pages needed for pagination
        $testimonialsPerPage = 2;
        $totalPages = ceil($testimonials->count() / $testimonialsPerPage);
        
        // Get active service categories for services section
        $serviceCategories = ServiceCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        return view('home', compact('testimonials', 'totalPages', 'serviceCategories'));
    }
}