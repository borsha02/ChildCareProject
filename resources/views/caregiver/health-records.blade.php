@extends('layouts.caregiver')

@section('title', 'Health Records')

@section('styles')
    @vite(['resources/css/caregiver/health-records.css'])
@endsection

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <div style="display: flex; align-items: center;">
            <a href="{{ route('caregiver.dashboard') }}" class="back-dashboard-icon">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Health Records</h1>
        </div>
        <div class="top-bar-actions">
            <!-- Actions removed as per request -->
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-details">
                    <h3>{{ $allergiesCount }}</h3>
                    <p>Children w/ Allergies</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-pills"></i></div>
                <div class="stat-details">
                    <h3>{{ $medicationsCount }}</h3>
                    <p>Active Medications</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-weight"></i></div>
                <div class="stat-details">
                    <h3>{{ $recordsCount }}</h3>
                    <p>Health Records</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Children Health Overview</h3>
            </div>
            <div class="children-list">
                @forelse($assignedChildren as $child)
                    <div class="child-item">
                        <div class="child-avatar">
                            {{ substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1) }}
                        </div>
                        <div class="child-info">
                            <h4 class="child-name">{{ $child->first_name }} {{ $child->last_name }}</h4>
                            
                            <div class="health-summary-grid">
                                <div>
                                    <strong class="info-label label-red">Allergies:</strong>
                                    <p class="info-text">{{ $child->allergies ?: 'None' }}</p>
                                </div>
                                <div>
                                    <strong class="info-label label-orange">Active Medications:</strong>
                                    @if($child->medications->count() > 0)
                                        <ul class="medication-list">
                                            @foreach($child->medications as $med)
                                                <li>{{ $med->medication_name }} ({{ $med->dosage }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="info-text">None</p>
                                    @endif
                                </div>
                            </div>

                            @if($child->healthRecords->isNotEmpty())
                                <div class="latest-update-card">
                                    <strong class="update-header">Latest Update ({{ $child->healthRecords->first()->record_date->format('M d, Y') }})</strong>
                                    <div class="update-stats">
                                        <span><i class="fas fa-weight"></i> {{ $child->healthRecords->first()->weight ?? '--' }} kg</span>
                                        <span><i class="fas fa-ruler-vertical"></i> {{ $child->healthRecords->first()->height ?? '--' }} cm</span>
                                    </div>
                                    @if($child->healthRecords->first()->notes)
                                        <p class="update-notes">"{{ $child->healthRecords->first()->notes }}"</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="action-container">
                            <a href="{{ route('caregiver.health.show', $child->id) }}" class="btn-view">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No assigned children found.</div>
                @endforelse
            </div>
        </div>
    </div>

        </div>
    </div>
@endsection


