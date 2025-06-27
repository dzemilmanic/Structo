@vite('resources/css/jobs.css')
@vite('resources/js/jobs.js')
<!-- Job Request Modal -->
<div id="jobRequestModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Send Job Request</h2>
            <button class="modal-close" onclick="closeJobRequestModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="jobRequestForm" method="POST" class="job-request-form">
                @csrf
                <div class="form-group">
                    <label for="jr_message">Message to Client *</label>
                    <textarea id="jr_message" name="message" required class="form-control" rows="4" placeholder="Explain why you're the right professional for this job..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="jr_proposed_price">Proposed Price ($)</label>
                    <input type="number" id="jr_proposed_price" name="proposed_price" class="form-control" min="0" placeholder="Enter your proposed price for this job">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeJobRequestModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>