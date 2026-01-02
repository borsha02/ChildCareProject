@extends('layouts.caregiver')

@section('title', 'Child Health Details')

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <div style="display: flex; alignItems: center; gap: 10px;">
             <a href="{{ route('caregiver.health') }}" style="color: #6b7280; font-size: 18px;"><i class="fas fa-arrow-left"></i></a>
             <h1 style="margin: 0;">Health Details: {{ $child->first_name }}</h1>
        </div>
    </div>

    <div class="content-area">
        <!-- Child Header -->
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 20px; align-items: center; padding: 20px;">
                <div class="child-avatar" style="width: 80px; height: 80px; font-size: 24px; border-radius: 50%; background: #3b82f6; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    {{ substr($child->first_name, 0, 1) . substr($child->last_name, 0, 1) }}
                </div>
                <div>
                    <h2 style="margin: 0 0 10px 0; color: #1f2937;">{{ $child->first_name }} {{ $child->last_name }}</h2>
                    <div style="display: flex; gap: 20px; color: #4b5563; font-size: 14px;">
                        <span><i class="fas fa-birthday-cake"></i> DOB: {{ $child->dob->format('M d, Y') }}</span>
                        <span><i class="fas fa-tint"></i> Blood Group: {{ $child->blood_group ?: 'N/A' }}</span>
                        <span><i class="fas fa-notes-medical"></i> Emerg. Contact: {{ $child->emergency_contact }}</span>
                    </div>
                     <div style="margin-top: 10px;">
                        <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            Allergies: {{ $child->allergies ?: 'None' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Medications -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-pills" style="color: #f59e0b;"></i> Medications</h3>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    @forelse($child->medications as $med)
                        <div style="padding: 15px; border-bottom: 1px solid #f3f4f6;">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <h4 style="margin: 0; color: #1f2937;">{{ $med->medication_name }}</h4>
                                <span style="font-size: 12px; padding: 2px 8px; border-radius: 10px; background: {{ $med->status == 'active' ? '#d1fae5' : '#e5e7eb'}}; color: {{ $med->status == 'active' ? '#065f46' : '#374151'}};">
                                    {{ ucfirst($med->status) }}
                                </span>
                            </div>
                            <p style="font-size: 13px; color: #4b5563; margin: 5px 0;">Dosage: {{ $med->dosage }} • {{ $med->frequency }}</p>
                            <p style="font-size: 12px; color: #6b7280; margin: 0;">{{ $med->start_date->format('M d') }} - {{ $med->end_date ? $med->end_date->format('M d, Y') : 'Ongoing' }}</p>
                            @if($med->notes)
                                <p style="font-size: 13px; color: #4b5563; margin-top: 5px; font-style: italic;">"{{ $med->notes }}"</p>
                            @endif
                        </div>
                    @empty
                        <div style="padding: 20px; text-align: center; color: #6b7280;">No medication records.</div>
                    @endforelse
                </div>
            </div>

            <!-- Vaccinations -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-syringe" style="color: #3b82f6;"></i> Vaccinations</h3>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                         <thead>
                            <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <th style="padding: 10px; text-align: left;">Vaccine</th>
                                <th style="padding: 10px; text-align: left;">Date</th>
                                <th style="padding: 10px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($child->vaccinations as $vax)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 10px; color: #1f2937; font-weight: 500;">{{ $vax->vaccine_name }}</td>
                                    <td style="padding: 10px; color: #4b5563;">{{ $vax->vaccination_date ? $vax->vaccination_date->format('M d, Y') : '--' }}</td>
                                    <td style="padding: 10px;">
                                        <span style="font-size: 12px; padding: 2px 8px; border-radius: 10px; background: {{ $vax->status == 'completed' ? '#d1fae5' : '#fef3c7'}}; color: {{ $vax->status == 'completed' ? '#065f46' : '#92400e'}};">
                                            {{ ucfirst($vax->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 20px; text-align: center; color: #6b7280;">No vaccination records.</td>
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
                <div style="max-height: 300px; overflow-y: auto;">
                    @forelse($child->checkups as $checkup)
                        <div style="padding: 15px; border-bottom: 1px solid #f3f4f6;">
                            <div style="display: flex; justify-content: space-between;">
                                <h4 style="margin: 0; color: #1f2937;">{{ $checkup->doctor_name }}</h4>
                                <span style="font-size: 12px; color: #6b7280;">{{ $checkup->checkup_date->format('M d, Y') }}</span>
                            </div>
                             <p style="font-size: 13px; color: #4b5563; margin: 5px 0;">Type: {{ $checkup->checkup_type }}</p>
                            @if($checkup->notes)
                                <p style="font-size: 13px; color: #4b5563; margin-top: 5px; font-style: italic;">"{{ $checkup->notes }}"</p>
                            @endif
                        </div>
                    @empty
                        <div style="padding: 20px; text-align: center; color: #6b7280;">No checkup records.</div>
                    @endforelse
                </div>
            </div>

            <!-- Health History -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-history" style="color: #6b7280;"></i> Health History (Log)</h3>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                         <thead>
                            <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <th style="padding: 10px; text-align: left;">Date</th>
                                <th style="padding: 10px; text-align: left;">Weight</th>
                                <th style="padding: 10px; text-align: left;">Height</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($child->healthRecords as $record)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 10px; color: #4b5563;">{{ $record->record_date->format('M d, Y') }}</td>
                                    <td style="padding: 10px; color: #1f2937;">{{ $record->weight }} kg</td>
                                    <td style="padding: 10px; color: #1f2937;">{{ $record->height }} cm</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 20px; text-align: center; color: #6b7280;">No health logs.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
