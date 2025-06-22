<!-- Service Request Modal -->
<div id="serviceRequestModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Request Service</h2>
            <button class="modal-close" onclick="closeServiceRequestModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="serviceRequestForm" method="POST" class="service-request-form">
                @csrf
                <div class="form-group">
                    <label for="sr_message">Message to Professional *</label>
                    <textarea id="sr_message" name="message" required class="form-control" rows="3"
                              placeholder="Describe why you want to hire this professional..."></textarea>
                </div>

                <div class="form-group">
                    <label for="sr_job_description">Job Description *</label>
                    <textarea id="sr_job_description" name="job_description" required class="form-control" rows="4"
                              placeholder="Describe what needs to be done in detail..."></textarea>
                </div>

                <div class="form-group">
                    <label for="sr_budget">Budget ($)</label>
                    <input type="number" id="sr_budget" name="budget" class="form-control" min="0"
                           placeholder="Enter approximate budget">
                </div>

                <div class="form-group">
                    <label for="sr_location">Location *</label>
                    <input type="text" id="sr_location" name="location" required class="form-control"
                           placeholder="Enter address">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeServiceRequestModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>