@extends('layouts.caregiver')

@section('title', 'Health Records')

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Health Records</h1>
        <div class="top-bar-actions">
            <!-- Actions removed as per request -->
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
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
                    <div class="child-item" style="display: flex; gap: 15px; padding: 15px; border-bottom: 1px solid #f3f4f6; align-items: flex-start;">
                        <div class="child-avatar" style="width: 50px; height: 50px; border-radius: 50%; background: #3b82f6; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px;">
                            {{ substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1) }}
                        </div>
                        <div class="child-info" style="flex: 1;">
                            <h4 style="margin: 0 0 5px 0; color: #1f2937;">{{ $child->first_name }} {{ $child->last_name }}</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
                                <div>
                                    <strong style="color: #ef4444; font-size: 13px;">Allergies:</strong>
                                    <p style="margin: 2px 0; color: #4b5563; font-size: 14px;">{{ $child->allergies ?: 'None' }}</p>
                                </div>
                                <div>
                                    <strong style="color: #f59e0b; font-size: 13px;">Active Medications:</strong>
                                    @if($child->medications->count() > 0)
                                        <ul style="margin: 2px 0; padding-left: 20px; color: #4b5563; font-size: 14px;">
                                            @foreach($child->medications as $med)
                                                <li>{{ $med->medication_name }} ({{ $med->dosage }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p style="margin: 2px 0; color: #4b5563; font-size: 14px;">None</p>
                                    @endif
                                </div>
                            </div>

                            @if($child->healthRecords->isNotEmpty())
                                <div style="margin-top: 10px; background: #f9fafb; padding: 10px; border-radius: 6px;">
                                    <strong style="color: #059669; font-size: 12px; text-transform: uppercase;">Latest Update ({{ $child->healthRecords->first()->record_date->format('M d, Y') }})</strong>
                                    <div style="display: flex; gap: 20px; margin-top: 5px; font-size: 14px;">
                                        <span><i class="fas fa-weight"></i> {{ $child->healthRecords->first()->weight ?? '--' }} kg</span>
                                        <span><i class="fas fa-ruler-vertical"></i> {{ $child->healthRecords->first()->height ?? '--' }} cm</span>
                                    </div>
                                    @if($child->healthRecords->first()->notes)
                                        <p style="margin-top: 5px; font-style: italic; color: #6b7280; font-size: 13px;">"{{ $child->healthRecords->first()->notes }}"</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div style="align-self: flex-start; margin-left: 10px;">
                            <a href="{{ route('caregiver.health.show', $child->id) }}" 
                               style="padding: 6px 12px; background: #3b82f6; color: white; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-block;">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="padding: 20px; text-align: center; color: #6b7280;">No assigned children found.</div>
                @endforelse
            </div>
        </div>
    </div>

        </div>
    </div>
@endsection


