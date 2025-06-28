<!-- Job Detail Modal -->
<div id="jobDetailModal" class="modal" style="display: none;">
    <div class="modal-content modal-content-large">
        <div class="modal-header">
            <h2><i class="fas fa-briefcase"></i> Job Details</h2>
            <button class="modal-close" onclick="closeJobDetailModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="job-detail-content">
                <!-- Job Header -->
                <div class="job-detail-header">
                    <h1 class="modal-job-title">Job Title</h1>
                    <span class="job-status modal-job-status">Status</span>
                </div>

                <!-- Job Meta Information -->
                <div class="job-detail-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span class="meta-label">Posted by:</span>
                        <a href="#" class="modal-job-client-link user-profile-link" style="display: none;"></a>
                        <span class="modal-job-client"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span class="meta-label">Category:</span>
                        <span class="modal-job-category"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span class="meta-label">Budget:</span>
                        <span class="modal-job-budget"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="meta-label">Location:</span>
                        <span class="modal-job-location"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span class="meta-label">Deadline:</span>
                        <span class="modal-job-deadline"></span>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="job-detail-section">
                    <h3><i class="fas fa-file-text"></i> Description</h3>
                    <div class="job-detail-description modal-job-description">
                        Job description will appear here...
                    </div>
                </div>

                <!-- Assigned Professional -->
                <div class="job-detail-section modal-job-assigned-section" style="display: none;">
                    <h3><i class="fas fa-user-check"></i> Assigned Professional</h3>
                    <div class="assigned-professional-info">
                        <a href="#" class="modal-job-assigned-link user-profile-link" style="display: none;"></a>
                        <span class="modal-job-assigned"></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="job-detail-actions">
                    <button onclick="closeJobDetailModal()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>