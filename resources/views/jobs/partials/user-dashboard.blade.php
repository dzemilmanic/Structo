@section('content')
<!-- User Dashboard -->
<div class="user-dashboard">
    <div class="dashboard-header">
        <h1>Moji Poslovi</h1>
        <button class="btn btn-primary" onclick="openJobModal()">
            <i class="icon-plus"></i> Postavi Novi Posao
        </button>
    </div>

    <!-- My Jobs Section -->
    @section('my-jobs')
    <section class="dashboard-section">
        <h2>Moji Postavljeni Poslovi</h2>
        <div class="jobs-grid">
            @forelse($jobs as $job)
                <div class="job-card">
                    <div class="job-header">
                        <h3>{{ $job->title }}</h3>
                        <span class="job-status status-{{ $job->status }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                    <p class="job-description">{{ Str::limit($job->description, 100) }}</p>
                    <div class="job-details">
                        <div class="category">{{ $job->category }}</div>
                        @if($job->budget)
                            <div class="budget">
                                <i class="icon-dollar"></i> {{ number_format($job->budget, 0) }} RSD
                            </div>
                        @endif
                        <div class="location">
                            <i class="icon-location"></i> {{ $job->location }}
                        </div>
                        @if($job->deadline)
                            <div class="deadline">
                                <i class="icon-calendar"></i> {{ $job->deadline->format('d.m.Y') }}
                            </div>
                        @endif
                    </div>
                    @if($job->assignedProfessional)
                        <div class="assigned-professional">
                            <strong>Dodeljeno:</strong> {{ $job->assignedProfessional->name }}
                        </div>
                    @endif
                    
                    @if($job->isOpen())
                        <div class="job-requests">
                            <h4>Zahtevi Profesionalaca ({{ $job->requests->count() }})</h4>
                            @foreach($job->requests as $request)
                                <div class="request-item">
                                    <div class="request-header">
                                        <strong>{{ $request->professional->name }}</strong>
                                        @if($request->professional->specialization)
                                            <span class="specialization">{{ $request->professional->specialization }}</span>
                                        @endif
                                    </div>
                                    <p class="request-message">{{ $request->message }}</p>
                                    @if($request->proposed_price)
                                        <p class="proposed-price">
                                            <strong>Predložena cena:</strong> {{ number_format($request->proposed_price, 0) }} RSD
                                        </p>
                                    @endif
                                    @if($request->isPending())
                                        <div class="request-actions">
                                            <form action="{{ route('job-requests.accept', $request) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Prihvati</button>
                                            </form>
                                            <form action="{{ route('job-requests.reject', $request) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Odbij</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="icon-briefcase"></i>
                    <p>Još nemate postavljenih poslova</p>
                    <button class="btn btn-secondary" onclick="openJobModal()">Postavi Prvi Posao</button>
                </div>
            @endforelse
        </div>
    </section>
    @endsection

    <!-- Available Services Section -->
    @section('available-services')
    <section class="dashboard-section">
        <h2>Dostupne Usluge</h2>
        <div class="services-grid">
            @forelse($availableServices as $service)
                <div class="service-card">
                    <div class="service-header">
                        <h3>{{ $service->title }}</h3>
                        <div class="professional-info">
                            <strong>{{ $service->professional->name }}</strong>
                            @if($service->professional->specialization)
                                <span class="specialization">{{ $service->professional->specialization }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="service-description">{{ Str::limit($service->description, 100) }}</p>
                    <div class="service-details">
                        <span class="category">{{ $service->category }}</span>
                        @if($service->price_from || $service->price_to)
                            <span class="price">
                                @if($service->price_from && $service->price_to)
                                    {{ number_format($service->price_from, 0) }} - {{ number_format($service->price_to, 0) }} RSD
                                @elseif($service->price_from)
                                    Od {{ number_format($service->price_from, 0) }} RSD
                                @else
                                    Do {{ number_format($service->price_to, 0) }} RSD
                                @endif
                            </span>
                        @endif
                    </div>
                    <div class="service-area">
                        <i class="icon-location"></i> {{ $service->service_area }}
                    </div>
                    <div class="service-actions">
                        <button class="btn btn-primary" onclick="requestService({{ $service->id }})">
                            Zatraži Uslugu
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="icon-search"></i>
                    <p>Trenutno nema dostupnih usluga</p>
                </div>
            @endforelse
        </div>
    </section>
    @endsection

    <!-- My Service Requests Section -->
    @section('service-requests')
    <section class="dashboard-section">
        <h2>Moji Zahtevi za Usluge</h2>
        <div class="requests-list">
            @forelse($serviceRequests as $request)
                <div class="request-card">
                    <div class="request-header">
                        <h3>{{ $request->service->title }}</h3>
                        <span class="request-status status-{{ $request->status }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                    <div class="professional-info">
                        <strong>Profesionalac:</strong> {{ $request->service->professional->name }}
                    </div>
                    <p class="request-description">{{ $request->job_description }}</p>
                    <div class="request-details">
                        <div class="location">
                            <i class="icon-location"></i> {{ $request->location }}
                        </div>
                        @if($request->budget)
                            <div class="budget">
                                <i class="icon-dollar"></i> {{ number_format($request->budget, 0) }} RSD
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="icon-inbox"></i>
                    <p>Nemate zahteva za usluge</p>
                </div>
            @endforelse
        </div>
    </section>
    @endsection
</div>

<!-- Job Modal Section -->
@section('job-modal')
<div id="jobModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Postavi Novi Posao</h2>
            <button class="modal-close" onclick="closeJobModal()">&times;</button>
        </div>
        <form action="{{ route('jobs.store') }}" method="POST" class="job-form">
            @csrf
            <div class="form-group">
                <label for="job_title">Naslov Posla *</label>
                <input type="text" id="job_title" name="title" required class="form-control"
                       placeholder="npr. Ulaganje keramike u kupatilu">
            </div>

            <div class="form-group">
                <label for="job_category">Kategorija *</label>
                <select id="job_category" name="category" required class="form-control">
                    <option value="">Izaberite kategoriju</option>
                    <option value="keramika">Keramika</option>
                    <option value="elektro">Elektro instalacije</option>
                    <option value="voda">Vodovodne instalacije</option>
                    <option value="grejanje">Grejanje</option>
                    <option value="fasada">Fasaderski radovi</option>
                    <option value="krov">Krovni radovi</option>
                    <option value="stolarija">Stolarija</option>
                    <option value="bojenje">Bojenje i gletovanje</option>
                    <option value="drugo">Ostalo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="job_description">Opis Posla *</label>
                <textarea id="job_description" name="description" required class="form-control" rows="4"
                          placeholder="Detaljno opišite šta treba da se uradi..."></textarea>
            </div>

            <div class="form-group">
                <label for="job_budget">Budžet (RSD)</label>
                <input type="number" id="job_budget" name="budget" class="form-control" min="0"
                       placeholder="Unesite približan budžet">
            </div>

            <div class="form-group">
                <label for="job_location">Lokacija *</label>
                <input type="text" id="job_location" name="location" required class="form-control"
                       placeholder="Unesite adresu">
                <div id="map" class="map-container"></div>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            </div>

            <div class="form-group">
                <label for="job_deadline">Željen Rok</label>
                <input type="date" id="job_deadline" name="deadline" class="form-control">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeJobModal()">Otkaži</button>
                <button type="submit" class="btn btn-primary">Postavi Posao</button>
            </div>
        </form>
    </div>
</div>
@endsection

<!-- Service Request Modal Section -->
@section('service-request-modal')
<div id="serviceRequestModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Zatraži Uslugu</h2>
            <button class="modal-close" onclick="closeServiceRequestModal()">&times;</button>
        </div>
        <form id="serviceRequestForm" method="POST" class="service-request-form">
            @csrf
            <div class="form-group">
                <label for="sr_message">Poruka Profesionalcu *</label>
                <textarea id="sr_message" name="message" required class="form-control" rows="3"
                          placeholder="Opišite zašto želite da angažujete ovog profesionalca..."></textarea>
            </div>

            <div class="form-group">
                <label for="sr_job_description">Opis Posla *</label>
                <textarea id="sr_job_description" name="job_description" required class="form-control" rows="4"
                          placeholder="Detaljno opišite šta treba da se uradi..."></textarea>
            </div>

            <div class="form-group">
                <label for="sr_budget">Budžet (RSD)</label>
                <input type="number" id="sr_budget" name="budget" class="form-control" min="0"
                       placeholder="Unesite približan budžet">
            </div>

            <div class="form-group">
                <label for="sr_location">Lokacija *</label>
                <input type="text" id="sr_location" name="location" required class="form-control"
                       placeholder="Unesite adresu">
                <div id="serviceRequestMap" class="map-container"></div>
                <input type="hidden" id="sr_latitude" name="latitude">
                <input type="hidden" id="sr_longitude" name="longitude">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeServiceRequestModal()">Otkaži</button>
                <button type="submit" class="btn btn-primary">Pošalji Zahtev</button>
            </div>
        </form>
    </div>
</div>
@endsection
@endsection