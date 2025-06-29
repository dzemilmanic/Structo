/**
 * News Page JavaScript
 * Handles news fetching, filtering, pagination, and interactions
 */

class NewsPage {
    constructor() {
        // API Configuration - Using NewsAPI
        this.apiKey = 'd7647c4100d94a438a286dcfec2ae873'; // Replace with your actual API key
        this.baseUrl = 'https://newsapi.org/v2/everything';
        this.proxyUrl = 'https://api.allorigins.win/raw?url='; // CORS proxy
        
        // State
        this.currentPage = 1;
        this.articlesPerPage = 12;
        this.totalResults = 0;
        this.currentQuery = '';
        this.currentCategory = 'all';
        this.currentSort = 'publishedAt';
        this.articles = [];
        this.searchTimeout = null;
        this.isSearching = false;
        
        // DOM Elements
        this.loadingContainer = document.getElementById('loadingContainer');
        this.errorContainer = document.getElementById('errorContainer');
        this.newsResults = document.getElementById('newsResults');
        this.newsGrid = document.getElementById('newsGrid');
        this.searchInput = document.getElementById('searchInput');
        this.searchBtn = document.getElementById('searchBtn');
        this.categoryFilter = document.getElementById('categoryFilter');
        this.sortFilter = document.getElementById('sortFilter');
        this.resetFilters = document.getElementById('resetFilters');
        this.resultsTitle = document.getElementById('resultsTitle');
        this.resultsCount = document.getElementById('resultsCount');
        this.paginationContainer = document.getElementById('paginationContainer');
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        this.paginationNumbers = document.getElementById('paginationNumbers');
        this.retryBtn = document.getElementById('retryBtn');
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.loadNews();
        this.addSearchStyles();
    }
    
    setupEventListeners() {
        // Live search functionality
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => this.handleLiveSearch(e));
            this.searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.handleSearch();
                }
            });
        }
        
        // Search button click
        if (this.searchBtn) {
            this.searchBtn.addEventListener('click', () => this.handleSearch());
        }
        
        // Filter changes
        if (this.categoryFilter) {
            this.categoryFilter.addEventListener('change', () => this.handleFilterChange());
        }
        if (this.sortFilter) {
            this.sortFilter.addEventListener('change', () => this.handleFilterChange());
        }
        
        // Reset filters
        if (this.resetFilters) {
            this.resetFilters.addEventListener('click', () => this.handleResetFilters());
        }
        
        // Pagination
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.handlePrevPage());
        }
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.handleNextPage());
        }
        
        // Retry button
        if (this.retryBtn) {
            this.retryBtn.addEventListener('click', () => this.loadNews());
        }
        
        // Topic cards
        document.querySelectorAll('.topic-card').forEach(card => {
            card.addEventListener('click', () => {
                const topic = card.getAttribute('data-topic');
                this.handleTopicClick(topic);
            });
        });
    }
    
    handleLiveSearch(e) {
        const query = e.target.value.trim();
        
        // Clear previous timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        // Debounce search - wait 300ms after user stops typing
        this.searchTimeout = setTimeout(() => {
            if (query.length >= 2 || query.length === 0) {
                this.performLiveSearch(query);
            }
        }, 300);
    }
    
    performLiveSearch(query) {
        if (this.isSearching) return;
        
        this.isSearching = true;
        this.currentQuery = query;
        this.currentPage = 1;
        
        // Show loading state
        this.showSearchLoading();
        
        // Load news with new query
        this.loadNews().finally(() => {
            this.hideSearchLoading();
            this.isSearching = false;
        });
    }
    
    showSearchLoading() {
        // Add loading indicator to search button
        if (this.searchBtn) {
            this.searchBtn.style.opacity = '0.6';
            this.searchBtn.style.pointerEvents = 'none';
            
            // Add spinner
            const originalContent = this.searchBtn.innerHTML;
            this.searchBtn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-spinner">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
            `;
            
            // Store original content for restoration
            this.searchBtn.dataset.originalContent = originalContent;
        }
        
        // Add loading class to news grid
        if (this.newsGrid) {
            this.newsGrid.style.opacity = '0.7';
            this.newsGrid.style.pointerEvents = 'none';
        }
    }
    
    hideSearchLoading() {
        // Restore search button
        if (this.searchBtn && this.searchBtn.dataset.originalContent) {
            this.searchBtn.innerHTML = this.searchBtn.dataset.originalContent;
            this.searchBtn.style.opacity = '';
            this.searchBtn.style.pointerEvents = '';
            delete this.searchBtn.dataset.originalContent;
        }
        
        // Restore news grid
        if (this.newsGrid) {
            this.newsGrid.style.opacity = '';
            this.newsGrid.style.pointerEvents = '';
        }
    }
    
    addSearchStyles() {
        if (!document.querySelector('#news-search-styles')) {
            const style = document.createElement('style');
            style.id = 'news-search-styles';
            style.textContent = `
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                
                .search-spinner {
                    animation: spin 1s linear infinite;
                }
                
                .search-input:focus {
                    border-color: var(--primary-color);
                    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
                }
                
                .news-grid {
                    transition: opacity 0.2s ease;
                }
                
                .search-btn {
                    transition: opacity 0.2s ease;
                }
                
                .search-box {
                    position: relative;
                }
                
                .search-input {
                    transition: all 0.2s ease;
                }
                
                .search-input:focus {
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    async loadNews() {
        this.showLoading();
        
        try {
            const query = this.buildQuery();
            const response = await this.fetchNews(query);
            
            if (response.status === 'ok') {
                this.articles = response.articles || [];
                this.totalResults = response.totalResults || 0;
                this.displayNews();
                this.updatePagination();
                this.showResults();
            } else {
                throw new Error(response.message || 'Failed to fetch news');
            }
        } catch (error) {
            console.error('Error loading news:', error);
            // Fallback to mock data if API fails
            this.loadMockNews();
        }
    }
    
    buildQuery() {
        let query = this.currentQuery || 'construction OR architecture OR building OR infrastructure';
        
        // Add category-specific terms
        if (this.currentCategory !== 'all') {
            const categoryTerms = {
                'construction': 'construction building contractor',
                'architecture': 'architecture architectural design',
                'real-estate': 'real estate property development',
                'infrastructure': 'infrastructure civil engineering',
                'sustainability': 'green building sustainable construction',
                'technology': 'construction technology BIM 3D printing'
            };
            query = categoryTerms[this.currentCategory] || query;
        }
        
        return query;
    }
    
    async fetchNews(query) {
        try {
            // Build API URL
            const params = new URLSearchParams({
                q: query,
                apiKey: this.apiKey,
                language: 'en',
                sortBy: this.currentSort,
                page: this.currentPage,
                pageSize: this.articlesPerPage,
                domains: 'constructionnews.co.uk,architecturalrecord.com,enr.com,constructiondive.com,dezeen.com,archdaily.com'
            });
            
            const apiUrl = `${this.baseUrl}?${params.toString()}`;
            
            // Use CORS proxy to avoid CORS issues
            const proxyApiUrl = `${this.proxyUrl}${encodeURIComponent(apiUrl)}`;
            
            console.log('Fetching news from:', apiUrl);
            
            const response = await fetch(proxyApiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('API Response:', data);
            
            return data;
            
        } catch (error) {
            console.error('API fetch error:', error);
            throw error;
        }
    }
    
    loadMockNews() {
        console.log('Loading mock news data as fallback...');
        
        // Simulate API delay
        setTimeout(() => {
            this.articles = this.getMockArticles();
            this.totalResults = 156;
            this.displayNews();
            this.updatePagination();
            this.showResults();
        }, 500);
    }
    
    getMockArticles() {
        const mockArticles = [
            {
                title: "Revolutionary 3D Printing Technology Transforms Construction Industry",
                description: "New 3D printing techniques are enabling faster, more sustainable construction methods with reduced waste and improved precision in building design.",
                url: "https://example.com/3d-printing-construction",
                urlToImage: "https://images.pexels.com/photos/3862132/pexels-photo-3862132.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-15T10:30:00Z",
                source: { name: "Construction Today" }
            },
            {
                title: "Sustainable Architecture: Green Building Practices Gain Momentum",
                description: "Architects worldwide are embracing eco-friendly materials and energy-efficient designs to create buildings that minimize environmental impact.",
                url: "https://example.com/sustainable-architecture",
                urlToImage: "https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-14T14:20:00Z",
                source: { name: "Green Architecture" }
            },
            {
                title: "Smart Cities Initiative: IoT Integration in Urban Planning",
                description: "Cities are incorporating Internet of Things technology to improve infrastructure management, traffic flow, and citizen services.",
                url: "https://example.com/smart-cities-iot",
                urlToImage: "https://images.pexels.com/photos/374870/pexels-photo-374870.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-13T09:15:00Z",
                source: { name: "Urban Planning Weekly" }
            },
            {
                title: "Modular Construction Methods Reduce Building Time by 50%",
                description: "Prefabricated building components are revolutionizing construction timelines while maintaining quality and reducing costs.",
                url: "https://example.com/modular-construction",
                urlToImage: "https://images.pexels.com/photos/1216589/pexels-photo-1216589.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-12T16:45:00Z",
                source: { name: "Building Innovation" }
            },
            {
                title: "AI-Powered Design Tools Transform Architectural Workflows",
                description: "Artificial intelligence is helping architects create more efficient designs and optimize building performance through advanced modeling.",
                url: "https://example.com/ai-architecture",
                urlToImage: "https://images.pexels.com/photos/3862365/pexels-photo-3862365.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-11T11:30:00Z",
                source: { name: "Tech Architecture" }
            },
            {
                title: "Renewable Energy Integration in Modern Building Design",
                description: "New construction projects are incorporating solar panels, wind systems, and geothermal solutions as standard features.",
                url: "https://example.com/renewable-energy-buildings",
                urlToImage: "https://images.pexels.com/photos/433308/pexels-photo-433308.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-10T13:20:00Z",
                source: { name: "Energy & Buildings" }
            },
            {
                title: "Historic Building Restoration: Preserving Architectural Heritage",
                description: "Advanced techniques are being used to restore and preserve historic buildings while maintaining their original character.",
                url: "https://example.com/historic-restoration",
                urlToImage: "https://images.pexels.com/photos/161758/governor-s-mansion-montgomery-alabama-grand-staircase-161758.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-09T08:45:00Z",
                source: { name: "Heritage Architecture" }
            },
            {
                title: "Earthquake-Resistant Building Technologies Save Lives",
                description: "New seismic engineering techniques are making buildings safer in earthquake-prone regions through innovative structural design.",
                url: "https://example.com/earthquake-resistant",
                urlToImage: "https://images.pexels.com/photos/3862132/pexels-photo-3862132.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-08T15:10:00Z",
                source: { name: "Structural Engineering" }
            },
            {
                title: "Affordable Housing Solutions Through Innovative Construction",
                description: "New building methods and materials are making quality housing more accessible and affordable for communities worldwide.",
                url: "https://example.com/affordable-housing",
                urlToImage: "https://images.pexels.com/photos/280229/pexels-photo-280229.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-07T12:30:00Z",
                source: { name: "Housing Solutions" }
            },
            {
                title: "Virtual Reality Transforms Architectural Visualization",
                description: "VR technology is allowing clients to experience buildings before construction, improving design decisions and client satisfaction.",
                url: "https://example.com/vr-architecture",
                urlToImage: "https://images.pexels.com/photos/3862365/pexels-photo-3862365.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-06T10:15:00Z",
                source: { name: "Digital Architecture" }
            },
            {
                title: "Cross-Laminated Timber: The Future of Sustainable Construction",
                description: "CLT technology is enabling the construction of tall wooden buildings that are both sustainable and structurally sound.",
                url: "https://example.com/clt-construction",
                urlToImage: "https://images.pexels.com/photos/1216589/pexels-photo-1216589.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-05T14:45:00Z",
                source: { name: "Timber Construction" }
            },
            {
                title: "Building Information Modeling (BIM) Streamlines Construction",
                description: "BIM technology is improving collaboration between architects, engineers, and contractors, reducing errors and project delays.",
                url: "https://example.com/bim-construction",
                urlToImage: "https://images.pexels.com/photos/3862132/pexels-photo-3862132.jpeg?auto=compress&cs=tinysrgb&w=400",
                publishedAt: "2024-01-04T09:30:00Z",
                source: { name: "Construction Technology" }
            }
        ];
        
        // Filter and paginate based on current settings
        let filteredArticles = [...mockArticles];
        
        // Apply search filter
        if (this.currentQuery) {
            filteredArticles = filteredArticles.filter(article =>
                article.title.toLowerCase().includes(this.currentQuery.toLowerCase()) ||
                article.description.toLowerCase().includes(this.currentQuery.toLowerCase())
            );
        }
        
        // Apply category filter
        if (this.currentCategory !== 'all') {
            const categoryKeywords = {
                'construction': ['construction', 'building', 'contractor', 'modular'],
                'architecture': ['architecture', 'architectural', 'design'],
                'real-estate': ['housing', 'property', 'real estate'],
                'infrastructure': ['infrastructure', 'urban', 'cities'],
                'sustainability': ['sustainable', 'green', 'renewable', 'eco'],
                'technology': ['3D printing', 'AI', 'VR', 'BIM', 'technology']
            };
            
            const keywords = categoryKeywords[this.currentCategory] || [];
            filteredArticles = filteredArticles.filter(article => {
                const text = (article.title + ' ' + article.description).toLowerCase();
                return keywords.some(keyword => text.includes(keyword.toLowerCase()));
            });
        }
        
        // Update total results
        this.totalResults = filteredArticles.length;
        
        // Sort articles
        if (this.currentSort === 'publishedAt') {
            filteredArticles.sort((a, b) => new Date(b.publishedAt) - new Date(a.publishedAt));
        }
        
        // Paginate
        const startIndex = (this.currentPage - 1) * this.articlesPerPage;
        const endIndex = startIndex + this.articlesPerPage;
        
        return filteredArticles.slice(startIndex, endIndex);
    }
    
    displayNews() {
        if (this.articles.length === 0) {
            this.newsGrid.innerHTML = `
                <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--neutral-medium);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 20px; color: var(--primary-color);">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <h3 style="color: var(--neutral-dark); margin-bottom: 10px;">No articles found</h3>
                    <p>Try adjusting your search terms or filters</p>
                </div>
            `;
            return;
        }
        
        this.newsGrid.innerHTML = this.articles.map(article => `
            <article class="news-article">
                <div class="article-image">
                    <img src="${article.urlToImage || 'https://images.pexels.com/photos/3862132/pexels-photo-3862132.jpeg?auto=compress&cs=tinysrgb&w=400'}" 
                         alt="${article.title}" 
                         onerror="this.src='https://images.pexels.com/photos/3862132/pexels-photo-3862132.jpeg?auto=compress&cs=tinysrgb&w=400'">
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-source">${article.source.name}</span>
                        <span class="article-date">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            ${this.formatDate(article.publishedAt)}
                        </span>
                    </div>
                    <h3 class="article-title">${article.title}</h3>
                    <p class="article-description">${article.description || 'No description available.'}</p>
                    <a href="${article.url}" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15,3 21,3 21,9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>
                </div>
            </article>
        `).join('');
        
        // Update results info
        this.updateResultsInfo();
    }
    
    updateResultsInfo() {
        const startResult = (this.currentPage - 1) * this.articlesPerPage + 1;
        const endResult = Math.min(this.currentPage * this.articlesPerPage, this.totalResults);
        
        let title = 'Latest News';
        if (this.currentQuery) {
            title = `Search Results for "${this.currentQuery}"`;
        } else if (this.currentCategory !== 'all') {
            const categoryNames = {
                'construction': 'Construction',
                'architecture': 'Architecture',
                'real-estate': 'Real Estate',
                'infrastructure': 'Infrastructure',
                'sustainability': 'Sustainability',
                'technology': 'Technology'
            };
            title = `${categoryNames[this.currentCategory]} News`;
        }
        
        this.resultsTitle.textContent = title;
        this.resultsCount.textContent = `Showing ${startResult}-${endResult} of ${this.totalResults} articles`;
    }
    
    updatePagination() {
        const totalPages = Math.ceil(this.totalResults / this.articlesPerPage);
        
        // Update prev/next buttons
        this.prevBtn.disabled = this.currentPage === 1;
        this.nextBtn.disabled = this.currentPage === totalPages;
        
        // Generate page numbers
        this.paginationNumbers.innerHTML = this.generatePageNumbers(totalPages);
        
        // Show/hide pagination
        this.paginationContainer.style.display = totalPages > 1 ? 'flex' : 'none';
    }
    
    generatePageNumbers(totalPages) {
        const maxVisible = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage + 1 < maxVisible) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        let html = '';
        
        for (let i = startPage; i <= endPage; i++) {
            html += `
                <button class="page-number ${i === this.currentPage ? 'active' : ''}" 
                        onclick="newsPage.goToPage(${i})">${i}</button>
            `;
        }
        
        return html;
    }
    
    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 1) {
            return 'Yesterday';
        } else if (diffDays < 7) {
            return `${diffDays} days ago`;
        } else {
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }
    }
    
    // Event Handlers
    handleSearch() {
        this.currentQuery = this.searchInput.value.trim();
        this.currentPage = 1;
        this.loadNews();
    }
    
    handleFilterChange() {
        this.currentCategory = this.categoryFilter.value;
        this.currentSort = this.sortFilter.value;
        this.currentPage = 1;
        this.loadNews();
    }
    
    handleResetFilters() {
        this.searchInput.value = '';
        this.categoryFilter.value = 'all';
        this.sortFilter.value = 'publishedAt';
        this.currentQuery = '';
        this.currentCategory = 'all';
        this.currentSort = 'publishedAt';
        this.currentPage = 1;
        this.loadNews();
    }
    
    handlePrevPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.loadNews();
            this.scrollToTop();
        }
    }
    
    handleNextPage() {
        const totalPages = Math.ceil(this.totalResults / this.articlesPerPage);
        if (this.currentPage < totalPages) {
            this.currentPage++;
            this.loadNews();
            this.scrollToTop();
        }
    }
    
    goToPage(page) {
        this.currentPage = page;
        this.loadNews();
        this.scrollToTop();
    }
    
    handleTopicClick(topic) {
        const topicQueries = {
            'green-building': 'green building ',
            'smart-cities': 'smart cities ',
            '3d-printing': '3D printing ',
            'modular-construction': 'modular construction '
        };
        
        this.searchInput.value = topicQueries[topic] || topic;
        this.currentQuery = topicQueries[topic] || topic;
        this.currentPage = 1;
        this.loadNews();
        this.scrollToTop();
    }
    
    // UI State Management
    showLoading() {
        this.loadingContainer.style.display = 'flex';
        this.errorContainer.style.display = 'none';
        this.newsResults.style.display = 'none';
    }
    
    showError() {
        this.loadingContainer.style.display = 'none';
        this.errorContainer.style.display = 'flex';
        this.newsResults.style.display = 'none';
    }
    
    showResults() {
        this.loadingContainer.style.display = 'none';
        this.errorContainer.style.display = 'none';
        this.newsResults.style.display = 'block';
    }
    
    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.newsPage = new NewsPage();
});