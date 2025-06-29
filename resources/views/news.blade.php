@extends('layouts.app')
@section('title', 'News - Structo')

@section('styles')
@vite(['resources/css/news.css', 'resources/js/news.js'])
@endsection

@section('content')
<!-- News Hero Section -->
<section class="news-hero">
    <div class="container">
        <div class="news-hero-content">
            <h1>Construction & Architecture News</h1>
            <p>Stay updated with the latest trends, innovations, and developments in construction and architecture</p>
        </div>
    </div>
</section>

<!-- News Filters Section -->
<section class="news-filters">
    <div class="container">
        <div class="filters-wrapper">
            <div class="search-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search news articles..." class="search-input">
                    <button class="search-btn" id="searchBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="filter-controls">
                <div class="filter-group">
                    <label for="categoryFilter">Category:</label>
                    <select id="categoryFilter" class="filter-select">
                        <option value="all">All News</option>
                        <option value="construction">Construction</option>
                        <option value="architecture">Architecture</option>
                        <option value="real-estate">Real Estate</option>
                        <option value="infrastructure">Infrastructure</option>
                        <option value="sustainability">Sustainability</option>
                        <option value="technology">Technology</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sortFilter">Sort by:</label>
                    <select id="sortFilter" class="filter-select">
                        <option value="publishedAt">Latest</option>
                        <option value="relevancy">Relevancy</option>
                        <option value="popularity">Popularity</option>
                    </select>
                </div>
                
                <button class="filter-reset-btn" id="resetFilters">Reset Filters</button>
            </div>
        </div>
    </div>
</section>

<!-- News Content Section -->
<section class="news-content">
    <div class="container">
        <!-- Loading State -->
        <div class="loading-container" id="loadingContainer">
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Loading latest news...</p>
            </div>
        </div>
        
        <!-- Error State -->
        <div class="error-container" id="errorContainer" style="display: none;">
            <div class="error-message">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <h3>Unable to load news</h3>
                <p>Please check your internet connection and try again.</p>
                <button class="retry-btn" id="retryBtn">Try Again</button>
            </div>
        </div>
        
        <!-- News Results -->
        <div class="news-results" id="newsResults" style="display: none;">
            <div class="results-header">
                <h2 id="resultsTitle">Latest News</h2>
                <p class="results-count" id="resultsCount">Loading articles...</p>
            </div>
            
            <div class="news-grid" id="newsGrid">
                <!-- News articles will be populated here by JavaScript -->
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container" id="paginationContainer">
                <div class="pagination">
                    <button class="pagination-btn prev-btn" id="prevBtn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Previous
                    </button>
                    
                    <div class="pagination-numbers" id="paginationNumbers">
                        <!-- Page numbers will be populated by JavaScript -->
                    </div>
                    
                    <button class="pagination-btn next-btn" id="nextBtn">
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9,18 15,12 9,6"></polyline>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Topics Section -->
<section class="featured-topics">
    <div class="container">
        <div class="section-header">
            <h2>Popular Topics</h2>
            <p>Explore trending topics in construction and architecture</p>
        </div>
        
        <div class="topics-grid">
            <div class="topic-card" data-topic="green-building">
                <div class="topic-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5"></path>
                        <path d="M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <h3>Green Building</h3>
                <p>Sustainable construction practices and eco-friendly materials</p>
            </div>
            
            <div class="topic-card" data-topic="smart-cities">
                <div class="topic-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V7l8-4v18"></path>
                        <path d="M19 21V11l-6-4"></path>
                    </svg>
                </div>
                <h3>Smart Cities</h3>
                <p>Urban planning and intelligent infrastructure development</p>
            </div>
            
            <div class="topic-card" data-topic="3d-printing">
                <div class="topic-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6,9 6,2 18,2 18,9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                </div>
                <h3>3D Printing</h3>
                <p>Revolutionary construction techniques and additive manufacturing</p>
            </div>
            
            <div class="topic-card" data-topic="modular-construction">
                <div class="topic-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </div>
                <h3>Modular Construction</h3>
                <p>Prefabricated building methods and efficient assembly</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Initialize news page when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof NewsPage !== 'undefined') {
            new NewsPage();
        }
    });
</script>
@endsection