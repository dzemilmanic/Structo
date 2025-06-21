// Job Management JavaScript

// Global variables
let map;
let serviceRequestMap;
let marker;
let serviceRequestMarker;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Leaflet maps if containers exist
    if (document.getElementById('map')) {
        initializeMap();
    }
    
    // Set up form event listeners
    setupFormEventListeners();
    
    // Set up modal event listeners
    setupModalEventListeners();
    
    // Close modals on outside click
    setupModalOutsideClick();
});

// Map initialization
function initializeMap() {
    // Main job posting map
    if (document.getElementById('map')) {
        map = L.map('map').setView([44.8176, 20.4633], 13); // Belgrade coordinates
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            
            // Reverse geocoding to get address
            reverseGeocode(e.latlng.lat, e.latlng.lng, 'job_location');
        });
    }
}

function initializeServiceRequestMap() {
    if (document.getElementById('serviceRequestMap')) {
        serviceRequestMap = L.map('serviceRequestMap').setView([44.8176, 20.4633], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(serviceRequestMap);
        
        serviceRequestMap.on('click', function(e) {
            if (serviceRequestMarker) {
                serviceRequestMap.removeLayer(serviceRequestMarker);
            }
            
            serviceRequestMarker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(serviceRequestMap);
            
            document.getElementById('sr_latitude').value = e.latlng.lat;
            document.getElementById('sr_longitude').value = e.latlng.lng;
            
            // Reverse geocoding to get address
            reverseGeocode(e.latlng.lat, e.latlng.lng, 'sr_location');
        });
    }
}

// Reverse geocoding function
function reverseGeocode(lat, lng, inputId) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                document.getElementById(inputId).value = data.display_name;
            }
        })
        .catch(error => {
            console.error('Reverse geocoding error:', error);
        });
}

// Form event listeners
function setupFormEventListeners() {
    // Location input autocomplete
    const locationInputs = ['job_location', 'sr_location'];
    
    locationInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    if (this.value.length > 2) {
                        geocodeAddress(this.value, inputId);
                    }
                }, 500);
            });
        }
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
}

// Geocoding function for address search
function geocodeAddress(address, inputId) {
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=5`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                
                if (inputId === 'job_location' && map) {
                    if (marker) map.removeLayer(marker);
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                } else if (inputId === 'sr_location' && serviceRequestMap) {
                    if (serviceRequestMarker) serviceRequestMap.removeLayer(serviceRequestMarker);
                    serviceRequestMarker = L.marker([lat, lng]).addTo(serviceRequestMap);
                    serviceRequestMap.setView([lat, lng], 15);
                    document.getElementById('sr_latitude').value = lat;
                    document.getElementById('sr_longitude').value = lng;
                }
            }
        })
        .catch(error => {
            console.error('Geocoding error:', error);
        });
}

// Modal functions
function setupModalEventListeners() {
    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
}

function setupModalOutsideClick() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAllModals();
            }
        });
    });
}

// Service modal functions
function openServiceModal() {
    const modal = document.getElementById('serviceModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeServiceModal() {
    const modal = document.getElementById('serviceModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm(modal.querySelector('form'));
    }
}

// Job modal functions
function openJobModal() {
    const modal = document.getElementById('jobModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Initialize map after modal is shown
        setTimeout(() => {
            if (map) {
                map.invalidateSize();
            } else {
                initializeMap();
            }
        }, 100);
    }
}

function closeJobModal() {
    const modal = document.getElementById('jobModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm(modal.querySelector('form'));
        
        // Clear map marker
        if (marker && map) {
            map.removeLayer(marker);
            marker = null;
        }
    }
}

// Service request modal functions
function requestService(serviceId) {
    const modal = document.getElementById('serviceRequestModal');
    const form = document.getElementById('serviceRequestForm');
    
    if (modal && form) {
        form.action = `/services/${serviceId}/request`;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Initialize service request map
        setTimeout(() => {
            if (serviceRequestMap) {
                serviceRequestMap.invalidateSize();
            } else {
                initializeServiceRequestMap();
            }
        }, 100);
    }
}

function closeServiceRequestModal() {
    const modal = document.getElementById('serviceRequestModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        resetForm(modal.querySelector('form'));
        
        // Clear map marker
        if (serviceRequestMarker && serviceRequestMap) {
            serviceRequestMap.removeLayer(serviceRequestMarker);
            serviceRequestMarker = null;
        }
    }
}

function closeAllModals() {
    closeServiceModal();
    closeJobModal();
    closeServiceRequestModal();
}

// Job request functions
function acceptJobRequest(requestId) {
    if (confirm('Da li ste sigurni da želite da prihvatite ovaj zahtev?')) {
        showLoading();
        
        fetch(`/job-requests/${requestId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification('Zahtev je uspešno prihvaćen!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('Greška pri prihvatanju zahteva.', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('Greška pri prihvatanju zahteva.', 'error');
            console.error('Error:', error);
        });
    }
}

function rejectJobRequest(requestId) {
    if (confirm('Da li ste sigurni da želite da odbijete ovaj zahtev?')) {
        showLoading();
        
        fetch(`/job-requests/${requestId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification('Zahtev je odbijen.', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('Greška pri odbijanju zahteva.', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('Greška pri odbijanju zahteva.', 'error');
            console.error('Error:', error);
        });
    }
}

// Complete job function
function completeJob(jobId) {
    if (confirm('Da li ste sigurni da želite da označite ovaj posao kao završen?')) {
        showLoading();
        
        fetch(`/jobs/${jobId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification('Posao je označen kao završen!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('Greška pri označavanju posla kao završen.', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('Greška pri označavanju posla kao završen.', 'error');
            console.error('Error:', error);
        });
    }
}

// Utility functions
function getCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

function resetForm(form) {
    if (form) {
        form.reset();
        
        // Clear hidden inputs
        const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
        hiddenInputs.forEach(input => {
            if (input.name === 'latitude' || input.name === 'longitude') {
                input.value = '';
            }
        });
        
        // Remove validation classes
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('is-invalid', 'is-valid');
        });
    }
}

function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
    });
    
    // Additional validation for location fields
    if (form.querySelector('#job_location') || form.querySelector('#sr_location')) {
        const latField = form.querySelector('#latitude') || form.querySelector('#sr_latitude');
        const lngField = form.querySelector('#longitude') || form.querySelector('#sr_longitude');
        
        if (latField && lngField && (!latField.value || !lngField.value)) {
            const locationField = form.querySelector('#job_location') || form.querySelector('#sr_location');
            locationField.classList.add('is-invalid');
            showNotification('Molimo izaberite lokaciju na mapi.', 'error');
            isValid = false;
        }
    }
    
    return isValid;
}

function showLoading() {
    // Create loading overlay if it doesn't exist
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;
        overlay.innerHTML = '<div class="loading-spinner"></div>';
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 400px;
    `;
    
    // Set background color based on type
    switch (type) {
        case 'success':
            notification.style.backgroundColor = '#27AE60';
            break;
        case 'error':
            notification.style.backgroundColor = '#C0392B';
            break;
        case 'warning':
            notification.style.backgroundColor = '#F39C12';
            break;
        default:
            notification.style.backgroundColor = '#3498DB';
    }
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Export functions for global access
window.openServiceModal = openServiceModal;
window.closeServiceModal = closeServiceModal;
window.openJobModal = openJobModal;
window.closeJobModal = closeJobModal;
window.requestService = requestService;
window.closeServiceRequestModal = closeServiceRequestModal;
window.acceptJobRequest = acceptJobRequest;
window.rejectJobRequest = rejectJobRequest;
window.completeJob = completeJob;