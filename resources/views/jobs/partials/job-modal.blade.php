<!-- Job Modal -->
<div id="jobModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Post New Job</h2>
            <button class="modal-close" onclick="closeJobModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('jobs.store') }}" method="POST" class="job-form">
                @csrf
                <div class="form-group">
                    <label for="job_title">Job Title *</label>
                    <input type="text" id="job_title" name="title" required class="form-control"
                           placeholder="e.g. Kitchen tile installation">
                </div>

                <div class="form-group">
                    <label for="job_category">Category *</label>
                    <select id="job_category" name="category" required class="form-control">
                        <option value="">Select category</option>
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}">{{ $category->name }}</option>
                            @endforeach
                        @else
                            <option value="general">General</option>
                            <option value="tiles">Tiles</option>
                            <option value="electrical">Electrical</option>
                            <option value="plumbing">Plumbing</option>
                            <option value="heating">Heating</option>
                            <option value="facade">Facade Work</option>
                            <option value="roofing">Roofing</option>
                            <option value="carpentry">Carpentry</option>
                            <option value="painting">Painting</option>
                            <option value="other">Other</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="job_description">Job Description *</label>
                    <textarea id="job_description" name="description" required class="form-control" rows="4"
                              placeholder="Describe what needs to be done in detail..."></textarea>
                </div>

                <div class="form-group">
                    <label for="job_budget">Budget ($)</label>
                    <input type="number" id="job_budget" name="budget" class="form-control" min="0"
                           placeholder="Enter approximate budget">
                </div>

                <div class="form-group">
                    <label for="job_location">Location *</label>
                    <input type="text" id="job_location" name="location" required class="form-control"
                           placeholder="Enter address">
                </div>

                <div class="form-group">
                    <label for="job_deadline">Desired Deadline</label>
                    <input type="date" id="job_deadline" name="deadline" class="form-control">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeJobModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post Job</button>
                </div>
            </form>
        </div>
    </div>
</div>