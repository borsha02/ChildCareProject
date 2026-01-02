@extends('layouts.caregiver')

@section('title', 'Messages')

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Messages</h1>
        <div class="top-bar-actions">
            <button
                style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                <i class="fas fa-plus"></i> New Message
            </button>
        </div>
    </div>

    <div class="content-area">
        <div class="card">
            <div class="card-header">
                <h3>Recent Messages</h3>
            </div>
            <div style="display: grid; gap: 15px;">
                <div style="padding: 15px; background: #f9fafb; border-radius: 10px; border-left: 4px solid #059669;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <h4 style="color: #1f2937;">Maria Martinez (Emma's Parent)</h4>
                        <span style="color: #6b7280; font-size: 13px;">2 hours ago</span>
                    </div>
                    <p style="color: #4b5563;">Thank you for the daily report. Emma loved the art activity today!</p>
                </div>
                <div style="padding: 15px; background: #f9fafb; border-radius: 10px; border-left: 4px solid #059669;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <h4 style="color: #1f2937;">Admin Office</h4>
                        <span style="color: #6b7280; font-size: 13px;">1 day ago</span>
                    </div>
                    <p style="color: #4b5563;">Reminder: Staff meeting tomorrow at 3 PM in the conference room.</p>
                </div>
            </div>
        </div>
    </div>
@endsection


