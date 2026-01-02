@extends('layouts.caregiver')

@section('title', 'Daily Reports')

@section('content')
    <div class="top-bar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Daily Reports</h1>
        <div class="top-bar-actions">
            <button
                style="padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                <i class="fas fa-plus"></i> Create New Report
            </button>
            <a href="{{ route('caregiver.notifications') }}"
                class="icon-btn {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span class="notification-dot"></span>
            </a>
            <a href="{{ route('caregiver.messages') }}"
                class="icon-btn {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <div class="content-area">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-details">
                    <h3>8</h3>
                    <p>Reports Today</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h3>4</h3>
                    <p>Pending Reports</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3>45</h3>
                    <p>Completed This Week</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-details">
                    <h3>98%</h3>
                    <p>Completion Rate</p>
                </div>
            </div>
        </div>

        <!-- Create Report Form -->
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">
                <h3>Create Daily Report</h3>
            </div>
            <form style="display: grid; gap: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Select
                            Child</label>
                        <select style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                            <option>Select a child</option>
                            <option>Emma Martinez</option>
                            <option>Lucas Johnson</option>
                            <option>Olivia Williams</option>
                            <option>Ava Davis</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Date</label>
                        <input type="date" value="2025-12-23"
                            style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Mood</label>
                    <div style="display: flex; gap: 10px;">
                        <button type="button"
                            style="padding: 10px 20px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 24px;">😊</button>
                        <button type="button"
                            style="padding: 10px 20px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 24px;">😐</button>
                        <button type="button"
                            style="padding: 10px 20px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 24px;">😢</button>
                        <button type="button"
                            style="padding: 10px 20px; border: 2px solid #059669; border-radius: 8px; background: #d1fae5; cursor: pointer; font-size: 24px;">😄</button>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Meals</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Breakfast</label>
                            <select
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                <option>All</option>
                                <option>Most</option>
                                <option>Some</option>
                                <option>None</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Lunch</label>
                            <select
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                <option>All</option>
                                <option>Most</option>
                                <option>Some</option>
                                <option>None</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Snack</label>
                            <select
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                <option>All</option>
                                <option>Most</option>
                                <option>Some</option>
                                <option>None</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Nap Time</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Duration
                                (minutes)</label>
                            <input type="number" placeholder="90"
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #6b7280;">Quality</label>
                            <select
                                style="width: 100%; padding: 8px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                                <option>Excellent</option>
                                <option>Good</option>
                                <option>Fair</option>
                                <option>Poor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Activities
                        Participated</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <label
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" checked> Art & Crafts
                        </label>
                        <label
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" checked> Outdoor Play
                        </label>
                        <label
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox"> Music & Dance
                        </label>
                        <label
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" checked> Story Time
                        </label>
                        <label
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox"> Science
                        </label>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #1f2937;">Notes &
                        Observations</label>
                    <textarea rows="4" placeholder="Enter any observations, achievements, or concerns..."
                        style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button"
                        style="padding: 12px 24px; background: #f3f4f6; color: #4b5563; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        Save as Draft
                    </button>
                    <button type="submit"
                        style="padding: 12px 24px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Reports -->
        <div class="card">
            <div class="card-header">
                <h3>Recent Reports</h3>
                <select style="padding: 8px 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                    <option>All Children</option>
                    <option>Emma Martinez</option>
                    <option>Lucas Johnson</option>
                    <option>Olivia Williams</option>
                </select>
            </div>
            <div style="display: grid; gap: 15px;">
                <div style="padding: 15px; background: #f9fafb; border-radius: 10px; border-left: 4px solid #059669;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <h4 style="color: #1f2937; font-size: 16px; margin-bottom: 5px;">Emma Martinez - December 23,
                                2025</h4>
                            <p style="color: #6b7280; font-size: 13px;">Mood: 😄 Happy • Meals: All eaten • Nap: 90 mins
                                (Excellent)</p>
                        </div>
                        <span
                            style="padding: 6px 12px; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">Submitted</span>
                    </div>
                    <p style="color: #4b5563; font-size: 14px;">Emma had a wonderful day! She was very engaged during art
                        activities and created a beautiful painting. She played well with others during outdoor time.</p>
                </div>
                <div style="padding: 15px; background: #f9fafb; border-radius: 10px; border-left: 4px solid #059669;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <h4 style="color: #1f2937; font-size: 16px; margin-bottom: 5px;">Lucas Johnson - December 23,
                                2025</h4>
                            <p style="color: #6b7280; font-size: 13px;">Mood: 😊 Content • Meals: Most eaten • Nap: 75
                                mins (Good)</p>
                        </div>
                        <span
                            style="padding: 6px 12px; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">Submitted</span>
                    </div>
                    <p style="color: #4b5563; font-size: 14px;">Lucas enjoyed story time and participated actively. He was
                        a bit fussy during lunch but ate most of his meal. Good nap today.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
