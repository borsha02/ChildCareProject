@extends('layouts.caregiver')

@section('title', 'Child Health Details')

@section('styles')
    @vite(['resources/css/caregiver/child-health-details.css'])
@endsection

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <div style="display: flex; align-items: center;">
             <a href="{{ route('caregiver.dashboard') }}" class="back-dashboard-icon"><i class="fas fa-arrow-left"></i></a>
             <h1 class="page-title">Health Details: {{ $child->first_name }}</h1>
        </div>
    </div>

    <div class="content-area">
        <!-- Child Header -->
        <div class="card profile-card">
            <div class="profile-content">
                <div class="profile-avatar">
                    {{ substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1) }}
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ $child->first_name }} {{ $child->last_name }}</h2>
                    <div class="profile-stats">
                        <span><i class="fas fa-birthday-cake"></i> DOB: {{ $child->dob->format('M d, Y') }}</span>
                        <span><i class="fas fa-tint"></i> Blood Group: {{ $child->blood_group ?: 'N/A' }}</span>
                        <span><i class="fas fa-notes-medical"></i> Emerg. Contact: {{ $child->emergency_contact }}</span>
                    </div>
                     <div class="allergy-section">
                        <span class="allergy-badge">
                            Allergies: {{ $child->allergies ?: 'None' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="details-grid">
            <!-- Medications -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-pills" style="color: #f59e0b;"></i> Medications</h3>
                </div>
                <div class="scrollable-list">
                    @forelse($child->medications as $med)
                        <div class="list-item">
                            <div class="item-header">
                                <h4 class="item-title">{{ $med->medication_name }}</h4>
                                <span class="status-badge {{ $med->status == 'active' ? 'status-active' : 'status-inactive'}}">
                                    {{ ucfirst($med->status) }}
                                </span>
                            </div>
                            <p class="item-meta">Dosage: {{ $med->dosage }} • {{ $med->frequency }}</p>
                            <p class="item-date">{{ $med->start_date->format('M d') }} - {{ $med->end_date ? $med->end_date->format('M d, Y') : 'Ongoing' }}</p>
                            @if($med->notes)
                                <p class="item-notes">"{{ $med->notes }}"</p>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">No medication records.</div>
                    @endforelse
                </div>
            </div>

            <!-- Vaccinations -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-syringe" style="color: #3b82f6;"></i> Vaccinations</h3>
                </div>
                <div class="scrollable-list">
                    <table class="vaccine-table">
                         <thead>
                            <tr>
                                <th>Vaccine</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($child->vaccinations as $vax)
                                <tr>
                                    <td class="vaccine-name">{{ $vax->vaccine_name }}</td>
                                    <td class="vaccine-date">{{ $vax->vaccination_date ? $vax->vaccination_date->format('M d, Y') : '--' }}</td>
                                    <td>
                                        <span class="status-badge {{ $vax->status == 'completed' ? 'status-completed' : 'status-pending'}}">
                                            {{ ucfirst($vax->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-state">No vaccination records.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Checkups -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-md" style="color: #8b5cf6;"></i> Checkups</h3>
                </div>
                <div class="scrollable-list">
                    @forelse($child->checkups as $checkup)
                        <div class="list-item">
                            <div class="item-header">
                                <h4 class="item-title">{{ $checkup->doctor_name }}</h4>
                                <span class="item-date">{{ $checkup->checkup_date->format('M d, Y') }}</span>
                            </div>
                             <p class="item-meta">Type: {{ $checkup->checkup_type }}</p>
                            @if($checkup->notes)
                                <p class="item-notes">"{{ $checkup->notes }}"</p>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">No checkup records.</div>
                    @endforelse
                </div>
            </div>

            <!-- Health History -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-history" style="color: #6b7280;"></i> Health History (Log)</h3>
                </div>
                <div class="scrollable-list">
                    <table class="vaccine-table">
                         <thead>
                            <tr>
                                <th>Date</th>
                                <th>Weight</th>
                                <th>Height</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($child->healthRecords as $record)
                                <tr>
                                    <td class="vaccine-date">{{ $record->record_date->format('M d, Y') }}</td>
                                    <td style="color: #1f2937;">{{ $record->weight }} kg</td>
                                    <td style="color: #1f2937;">{{ $record->height }} cm</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-state">No health logs.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
