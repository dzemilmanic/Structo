<!-- Service Detail Modal -->
<div id="serviceDetailModal" class="modal" style="display: none;">
    <div class="modal-content modal-content-large">
        <div class="modal-header">
            <h2><i class="fas fa-tools"></i> Service Details</h2>
            <button class="modal-close" onclick="closeServiceDetailModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="service-detail-content">
                <!-- Service Header -->
                <div class="service-detail-header">
                    <h1 class="modal-service-title">Service Title</h1>
                    <span class="service-status modal-service-status">Status</span>
                </div>

                <!-- Service Meta Information -->
                <div class="service-detail-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span class="meta-label">Professional:</span>
                        <a href="#" class="modal-service-professional-link user-profile-link" style="display: none;"></a>
                        <span class="modal-service-professional"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-star"></i>
                        <span class="meta-label">Specialization:</span>
                        <span class="modal-service-specialization"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span class="meta-label">Category:</span>
                        <span class="modal-service-category"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span class="meta-label">Price:</span>
                        <span class="modal-service-price"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="meta-label">Service Area:</span>
                        <span class="modal-service-area"></span>
                    </div>
                </div>

                <!-- Service Description -->
                <div class="service-detail-section">
                    <h3><i class="fas fa-file-text"></i> Description</h3>
                    <div class="service-detail-description modal-service-description">
                        Service description will appear here...
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="service-detail-actions">
                    <button onclick="closeServiceDetailModal()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>