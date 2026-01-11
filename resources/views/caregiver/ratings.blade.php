@extends('layouts.caregiver')

@section('title', 'My Ratings')

@section('styles')
    @vite(['resources/css/caregiver/ratings.css'])
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
            <h1>My Ratings & Reviews</h1>
        </div>
        <div class="top-bar-actions">
            <a href="{{ route('caregiver.notifications') }}"
                class="icon-btn {{ request()->routeIs('caregiver.notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                @php
                    $unreadNotifications = Auth::user()->unreadNotifications->count();
                @endphp
                @if($unreadNotifications > 0)
                    <span class="notification-dot"></span>
                @endif
            </a>
            <a href="{{ route('caregiver.messages') }}"
                class="icon-btn {{ request()->routeIs('caregiver.messages') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <div class="content-area">
        @if($ratings->count() > 0)
            @php
                $averageRating = $ratings->avg('rating');
                $totalRatings = $ratings->count();
            @endphp
            
            <!-- Rating Summary Card -->
            <div class="rating-summary">
                <div class="rating-summary-content">
                    <div class="rating-summary-left">
                        <h3>Your Average Rating</h3>
                        <div class="average-rating">
                            <span class="score">{{ number_format($averageRating, 1) }}</span>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($averageRating))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="rating-summary-right">
                        <div class="total-ratings">{{ $totalRatings }}</div>
                        <div class="total-ratings-label">Total Reviews</div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon yellow">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-details">
                        <h3>{{ number_format($averageRating, 1) }}</h3>
                        <p>Average Rating</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-details">
                        <h3>{{ $totalRatings }}</h3>
                        <p>Total Reviews</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ratings List -->
        <div class="card">
            <div class="card-header">
                <h3>All Reviews</h3>
            </div>
            <div class="ratings-container">
                @forelse($ratings as $rating)
                <div class="rating-card">
                    <div class="rating-header">
                        <div class="rating-parent-info">
                            <div class="rating-parent-avatar">
                                P
                            </div>
                            <div class="rating-parent-details">
                                <h4>Anonymous Parent</h4>
                                <p>Verified Reviewer</p>
                            </div>
                        </div>
                        <div class="rating-meta">
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-score">{{ $rating->rating }}/5</span>
                            <span class="rating-date">{{ $rating->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    @if($rating->comment)
                    <div class="rating-comment">
                        <p>{{ $rating->comment }}</p>
                    </div>
                    @else
                    <div class="no-comment">No specific comment provided.</div>
                    @endif
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <h3>No Ratings Yet</h3>
                    <p>Ratings and reviews from parents will appear here.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
