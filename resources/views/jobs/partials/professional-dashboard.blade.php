@extends('layouts.app')

    @vite('resources/css/jobs.css')
    @vite('resources/js/jobs.js')
<!-- Professional Dashboard -->
<div class="professional-dashboard">
    <div class="dashboard-header">
        <h1>Profesionalni Dashboard</h1>
        <button class="btn btn-primary" onclick="openServiceModal()">
            <i class="icon-plus"></i> Dodaj Novu Uslugu
        </button>
    </div>

    <!-- My Services -->
    <section class="dashboard-section">
        <h2>Moje Usluge</h2>
        <div class="services-grid">
            @forelse($services as $service)
                <div class="service-card">
                    <div class="service-header">
                        <h3>{{ $service->title }}</h3>
                        <span class="service-status {{ $service->is_active ? 'active' : 'inactive' }}">
                            {{ $service->is_active ? 'Aktivna' : 'Neaktivna' }}
                        </span>
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
                </div>
            @empty
                <div class="empty-state">
                    <i class="icon-briefcase"></i>
                    <p>Još nemate kreiranih usluga</p>
                    <button class="btn btn-secondary" onclick="openServiceModal()">Dodaj Prvu Uslugu</button>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Job Requests -->
    <section class="dashboard-section">
        <h2>Zahtevi za Poslove</h2>
        <div class="requests-list">
            @forelse($jobRequests as $request)
                <div class="request-card">
                    <div class="request-header">
                        <h3>{{ $request->job->title }}</h3>
                        <span class="request-status status-{{ $request->status }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                    <p class="job-description">{{ Str::limit($request->job->description, 150) }}</p>
                    <div class="request-details">
                        <div class="client-info">
                            <strong>Klijent:</strong> {{ $request->job->user->name }}
                        </div>
                        <div class="location">
                            <i class="icon-location"></i> {{ $request->job->location }}
                        </div>
                        @if($request->job->budget)
                            <div class="budget">
                                <i class="icon-dollar"></i> {{ number_format($request->job->budget, 0) }} RSD
                            </div>
                        @endif
                    </div>
                    @if($request->isPending())
                        <div class="pending-message">
                            <p><strong>Vaša poruka:</strong> {{ $request->message }}</p>
                            @if($request->proposed_price)
                                <p><strong>Predložena cena:</strong> {{ number_format($request->proposed_price, 0) }} RSD</p>
                            @endif
                        </div>
                    @elseif($request->isAccepted())
                        <div class="accepted-message">
                            <p class="success">✓ Vaš zahtev je prihvaćen!</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="icon-inbox"></i>
                    <p>Nemate zahteva za poslove</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Assigned Jobs -->
    <section class="dashboard-section">
        <h2>Dodeljeni Poslovi</h2>
        <div class="assigned-jobs">
            @forelse($assignedJobs as $job)
                <div class="job-card">
                    <div class="job-header">
                        <h3>{{ $job->title }}</h3>
                        <span class="job-status status-{{ $job->status }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                    <p class="job-description">{{ Str::limit($job->description, 150) }}</p>
                    <div class="job-details">
                        <div class="client-info">
                            <strong>Klijent:</strong> {{ $job->user->name }}
                        </div>
                        <div class="location">
                            <i class="icon-location"></i> {{ $job->location }}
                        </div>
                        @if($job->deadline)
                            <div class="deadline">
                                <i class="icon-calendar"></i> {{ $job->deadline->format('d.m.Y') }}
                            </div>
                        @endif
                    </div>
                    @if($job->isInProgress())
                        <div class="job-actions">
                            <button class="btn btn-success" onclick="completeJob({{ $job->id }})">
                                Označi kao Završeno
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="icon-briefcase"></i>
                    <p>Nemate dodeljenih poslova</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<!-- Service Modal -->
<div id="serviceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Dodaj Novu Uslugu</h2>
            <button class="modal-close" onclick="closeServiceModal()">&times;</button>
        </div>
        <form action="{{ route('jobs.store') }}" method="POST" class="service-form">
            @csrf
            <div class="form-group">
                <label for="service_title">Naslov Usluge *</label>
                <input type="text" id="service_title" name="title" required class="form-control" 
                       placeholder="npr. Keramičarske usluge">
            </div>

            <div class="form-group">
                <label for="service_category">Kategorija *</label>
                <select id="service_category" name="category" required class="form-control">
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
                <label for="service_description">Opis Usluge *</label>
                <textarea id="service_description" name="description" required class="form-control" rows="4"
                          placeholder="Detaljno opišite vašu uslugu..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price_from">Cena od (RSD)</label>
                    <input type="number" id="price_from" name="price_from" class="form-control" min="0">
                </div>
                <div class="form-group">
                    <label for="price_to">Cena do (RSD)</label>
                    <input type="number" id="price_to" name="price_to" class="form-control" min="0">
                </div>
            </div>

            <div class="form-group">
                <label for="service_area">Oblast Rada *</label>
                <input type="text" id="service_area" name="service_area" required class="form-control"
                       placeholder="npr. Beograd i okolina">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeServiceModal()">Otkaži</button>
                <button type="submit" class="btn btn-primary">Kreiraj Uslugu</button>
            </div>
        </form>
    </div>
</div>