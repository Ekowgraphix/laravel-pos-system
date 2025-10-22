@extends('admin.layouts.master')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 15px;
        color: white;
        margin-bottom: 2rem;
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        object-fit: cover;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid;
        margin-bottom: 1rem;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    
    .stat-card.primary { border-color: #4e73df; }
    .stat-card.success { border-color: #1cc88a; }
    .stat-card.info { border-color: #36b9cc; }
    .stat-card.warning { border-color: #f6c23e; }
    .stat-card.danger { border-color: #e74a3b; }
    
    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    
    .progress-ring {
        width: 120px;
        height: 120px;
    }
    
    .activity-item {
        padding: 1rem;
        border-left: 3px solid #e3e6f0;
        margin-bottom: 1rem;
        position: relative;
        padding-left: 2rem;
    }
    
    .activity-item:before {
        content: '';
        position: absolute;
        left: -6px;
        top: 1.2rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #4e73df;
    }
    
    .quick-action-btn {
        padding: 1rem;
        border-radius: 10px;
        transition: all 0.3s;
        background: white;
        border: 2px solid #e3e6f0;
        text-align: center;
        display: block;
        color: #5a5c69;
        text-decoration: none;
    }
    
    .quick-action-btn:hover {
        border-color: #4e73df;
        color: #4e73df;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
        text-decoration: none;
    }
    
    .profile-completion-text {
        font-size: 2rem;
        font-weight: 700;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            text-align: center;
        }
    }
</style>

<div class="container-fluid">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                @if (auth()->user()->profile == null)
                    <img class="profile-avatar" id="headerAvatar"
                        src="{{ asset('admin/img/undraw_profile.svg') }}" alt="Profile">
                @else
                    <img class="profile-avatar" id="headerAvatar"
                        src="{{ asset('adminProfile/' . auth()->user()->profile) }}" alt="Profile">
                @endif
            </div>
            <div class="col-md-6">
                <h2 class="mb-1">
                    <i class="fas fa-user-shield me-2"></i>
                    {{ auth()->user()->name ?? auth()->user()->nickname }}
                </h2>
                <p class="mb-1">
                    <i class="fas fa-envelope me-2"></i>{{ auth()->user()->email }}
                </p>
                <p class="mb-0">
                    <i class="fas fa-crown me-2"></i>
                    <span class="badge bg-light text-dark px-3 py-2">Administrator</span>
                </p>
                <p class="mb-0 mt-2">
                    <small><i class="fas fa-clock me-1"></i>Member since {{ $activitySummary['member_since'] }}</small>
                </p>
            </div>
            <div class="col-md-3 text-center">
                <div class="profile-completion-text">{{ $profileCompletion }}%</div>
                <p class="mb-0">Profile Complete</p>
                <div class="progress mt-2" style="height: 8px;">
                    <div class="progress-bar bg-light" role="progressbar" style="width: {{ $profileCompletion }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Statistics -->
        <div class="col-lg-4 col-md-6 mb-4">
            <h5 class="mb-3">
                <i class="fas fa-chart-line me-2"></i>Quick Stats
            </h5>
            
            <div class="stat-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Total Products</div>
                        <div class="h3 mb-0">{{ number_format($stats['total_products']) }}</div>
                    </div>
                    <i class="fas fa-box stat-icon text-primary"></i>
                </div>
            </div>
            
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Total Orders</div>
                        <div class="h3 mb-0">{{ number_format($stats['total_orders']) }}</div>
                    </div>
                    <i class="fas fa-shopping-cart stat-icon text-success"></i>
                </div>
            </div>
            
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Total Revenue</div>
                        <div class="h3 mb-0">{{ number_format($stats['total_revenue']) }} GHS</div>
                    </div>
                    <i class="fas fa-dollar-sign stat-icon text-info"></i>
                </div>
            </div>
            
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Pending Orders</div>
                        <div class="h3 mb-0">{{ number_format($stats['pending_orders']) }}</div>
                    </div>
                    <i class="fas fa-clock stat-icon text-warning"></i>
                </div>
            </div>
            
            @if($stats['low_stock_items'] > 0)
            <div class="stat-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Low Stock Items</div>
                        <div class="h3 mb-0">{{ number_format($stats['low_stock_items']) }}</div>
                    </div>
                    <i class="fas fa-exclamation-triangle stat-icon text-danger"></i>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <h5 class="mb-3 mt-4">
                <i class="fas fa-bolt me-2"></i>Quick Actions
            </h5>
            <div class="row">
                <div class="col-6 mb-2">
                    <a href="{{ route('productList') }}" class="quick-action-btn">
                        <i class="fas fa-box fa-2x mb-2"></i>
                        <div class="small">Products</div>
                    </a>
                </div>
                <div class="col-6 mb-2">
                    <a href="{{ route('orderListPage') }}" class="quick-action-btn">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <div class="small">Orders</div>
                    </a>
                </div>
                <div class="col-6 mb-2">
                    <a href="{{ route('userList') }}" class="quick-action-btn">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <div class="small">Users</div>
                    </a>
                </div>
                <div class="col-6 mb-2">
                    <a href="{{ route('salesReport') }}" class="quick-action-btn">
                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                        <div class="small">Reports</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Middle Column - Profile Form -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </h6>
                </div>
                <form action="{{ route('adminProfileUpdate') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="oldImage" value="{{ auth()->user()->profile }}">

                        <div class="mb-3 text-center">
                            @if (auth()->user()->profile == null)
                                <img class="img-thumbnail" id="output"
                                    src="{{ asset('admin/img/undraw_profile.svg') }}" 
                                    style="max-width: 200px; height:200px; object-fit: cover; border-radius: 10px;">
                            @else
                                <img class="img-thumbnail" id="output"
                                    src="{{ asset('adminProfile/' . auth()->user()->profile) }}" 
                                    style="max-width: 200px; height:200px; object-fit: cover; border-radius: 10px;">
                            @endif
                            <div class="mt-2">
                                <label for="imageUpload" class="btn btn-sm btn-primary">
                                    <i class="fas fa-camera me-1"></i>Change Photo
                                </label>
                                <input type="file" name="image" id="imageUpload" style="display: none;"
                                    class="@error('image') is-invalid @enderror"
                                    onchange="loadFile(event)" accept="image/*">
                            </div>
                            @error('image')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-user me-1"></i>Name
                            </label>
                            <input type="text" name="name"
                                @if (auth()->user()->provider != 'simple') disabled @endif
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Your name"
                                value="{{ old('name', auth()->user()->name == null ? auth()->user()->nickname : auth()->user()->name) }}">
                            @error('name')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email
                            </label>
                            <input type="email" name="email"
                                @if (auth()->user()->provider != 'simple') disabled @endif
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="your@email.com"
                                value="{{ auth()->user()->email }}">
                            @error('email')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-phone me-1"></i>Phone
                            </label>
                            <input type="text" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="09xxxxxxxx"
                                value="{{ old('phone', auth()->user()->phone) }}">
                            @error('phone')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Address
                            </label>
                            <textarea name="address" rows="2"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Your address">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>

                        @if (auth()->user()->provider == 'simple')
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-key me-2"></i>
                                <div>
                                    <a href="{{ route('passwordChange') }}" class="alert-link">Change Password</a>
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                            <a href="{{ route('adminDashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Activity -->
        <div class="col-lg-4 mb-4">
            <h5 class="mb-3">
                <i class="fas fa-history me-2"></i>Recent Activity
            </h5>
            
            <div class="card shadow mb-4">
                <div class="card-body">
                    @forelse($recentOrders->take(5) as $order)
                        <div class="activity-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>Order #{{ $order->order_code }}</strong>
                                    <div class="small text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $order->user->name ?? 'Guest' }}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-box me-1"></i>{{ $order->product->name }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">{{ $order->created_at->diffForHumans() }}</div>
                                    <div class="badge 
                                        @if($order->status == '0') bg-warning
                                        @elseif($order->status == '1') bg-success
                                        @else bg-danger
                                        @endif">
                                        @if($order->status == '0') Pending
                                        @elseif($order->status == '1') Success
                                        @else Rejected
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-2"></i>
                            <p>No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Admin Info Card -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0">
                        <i class="fas fa-info-circle me-2"></i>Admin Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        <strong>Member Since:</strong>
                        <div class="text-muted small">{{ $activitySummary['member_since'] }}</div>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-clock text-success me-2"></i>
                        <strong>Last Active:</strong>
                        <div class="text-muted small">{{ $activitySummary['last_login'] }}</div>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-users text-info me-2"></i>
                        <strong>Total Users:</strong>
                        <div class="text-muted small">{{ number_format($stats['total_users']) }} users</div>
                    </div>
                    <div>
                        <i class="fas fa-user-shield text-warning me-2"></i>
                        <strong>Total Admins:</strong>
                        <div class="text-muted small">{{ number_format($stats['total_admins']) }} admins</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function loadFile(event) {
        const output = document.getElementById('output');
        const headerAvatar = document.getElementById('headerAvatar');
        output.src = URL.createObjectURL(event.target.files[0]);
        headerAvatar.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    }
</script>
@endsection
