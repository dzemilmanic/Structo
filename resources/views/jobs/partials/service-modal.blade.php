<!-- Service Modal -->
<div id="serviceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Service</h2>
            <button class="modal-close" onclick="closeServiceModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('services.store') }}" method="POST" class="service-form">
                @csrf
                <div class="form-group">
                    <label for="service_title">Service Title *</label>
                    <input type="text" id="service_title" name="title" required class="form-control" 
                           placeholder="e.g. Tile Installation Services">
                </div>

                <div class="form-group">
                    <label for="service_category">Category *</label>
                    <select id="service_category" name="category" required class="form-control">
                        <option value="">Select category</option>
                        <option value="tiles">Tiles</option>
                        <option value="electrical">Electrical</option>
                        <option value="plumbing">Plumbing</option>
                        <option value="heating">Heating</option>
                        <option value="facade">Facade Work</option>
                        <option value="roofing">Roofing</option>
                        <option value="carpentry">Carpentry</option>
                        <option value="painting">Painting</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="service_description">Service Description *</label>
                    <textarea id="service_description" name="description" required class="form-control" rows="4"
                              placeholder="Describe your service in detail..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price_from">Price From ($)</label>
                        <input type="number" id="price_from" name="price_from" class="form-control" min="0"
                               placeholder="Minimum price">
                    </div>
                    <div class="form-group">
                        <label for="price_to">Price To ($)</label>
                        <input type="number" id="price_to" name="price_to" class="form-control" min="0"
                               placeholder="Maximum price">
                    </div>
                </div>

                <div class="form-group">
                    <label for="service_area">Service Area *</label>
                    <input type="text" id="service_area" name="service_area" required class="form-control"
                           placeholder="e.g. New York and surrounding areas">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeServiceModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Service</button>
                </div>
            </form>
        </div>
    </div>
</div>