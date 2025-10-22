@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-users"></i>
                User Management
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-user-friends"></i> Manage customers and user roles
            </p>
        </div>

        <!-- User Management Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-user-friends"></i>
                    Customer List
                </h5>
            </div>
            <div class="card-body-modern">
                <!-- Search Bar -->
                <form action="{{ route('userList') }}" method="get" class="mb-4">
                    <div class="search-bar-modern">
                        <input type="text" 
                               name="searchKey" 
                               placeholder="Search by name, email, phone..."
                               value="{{ request('searchKey') }}">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Tab Navigation -->
                <div class="d-flex gap-3 mb-4" style="gap: 15px;">
                    <a href="{{ route('adminList') }}" 
                       class="btn-warning-modern btn-modern" 
                       style="position: relative; padding-right: 50px;">
                        <i class="fas fa-user-shield"></i>
                        Admin List
                        <span class="badge-primary-modern badge-modern" 
                              style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); padding: 4px 10px; font-size: 12px;">
                            {{ $adminCount }}
                        </span>
                    </a>
                    <a href="{{ route('userList') }}" 
                       class="btn-info-modern btn-modern" 
                       style="position: relative; padding-right: 50px;">
                        <i class="fas fa-users"></i>
                        Customer List
                        <span class="badge-primary-modern badge-modern" 
                              style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); padding: 4px 10px; font-size: 12px;">
                            {{ $data->total() }}
                        </span>
                    </a>
                </div>

                <!-- Customer Table -->
                <div class="table-modern-wrapper">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Name</th>
                                <th style="width: 20%;">Email</th>
                                <th style="width: 15%;">Phone</th>
                                <th style="width: 20%;">Address</th>
                                @if (auth()->user()->role == 'superadmin')
                                    <th style="width: 25%; text-align: center;">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('accountProfile', $item->id) }}" 
                                           style="color: #667eea; font-weight: 700; text-decoration: none;">
                                            <i class="fas fa-user-circle me-2"></i>
                                            @if ($item->name != null)
                                                {{ $item->name }}
                                            @else
                                                {{ $item->nickname }}
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope me-2" style="color: #9ca3af;"></i>
                                        {{ $item->email }}
                                    </td>
                                    <td>
                                        @if($item->phone)
                                            <i class="fas fa-phone me-2" style="color: #9ca3af;"></i>
                                            {{ $item->phone }}
                                        @else
                                            <span style="color: #9ca3af; font-style: italic;">Not provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->address)
                                            <i class="fas fa-map-marker-alt me-2" style="color: #9ca3af;"></i>
                                            {{ Str::limit($item->address, 30) }}
                                        @else
                                            <span style="color: #9ca3af; font-style: italic;">Not provided</span>
                                        @endif
                                    </td>

                                    @if (auth()->user()->role == 'superadmin')
                                        <td>
                                            <div class="action-buttons-modern" style="justify-content: center;">
                                                <a href="{{ route('accountProfile', $item->id) }}" 
                                                   class="action-btn-modern action-btn-view"
                                                   title="View Profile">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('changeAdminRole', $item->id) }}" 
                                                   class="action-btn-modern action-btn-edit"
                                                   title="Promote to Admin"
                                                   onclick="return confirm('Promote this user to admin role?')">
                                                    <i class="fas fa-user-plus"></i>
                                                </a>
                                                <a href="{{ route('deleteUserAccount', $item->id) }}" 
                                                   class="action-btn-modern action-btn-delete"
                                                   onclick="return confirm('Are you sure you want to delete this user account?')"
                                                   title="Delete User">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role == 'superadmin' ? '5' : '4' }}" style="text-align: center; padding: 40px;">
                                        <i class="fas fa-user-slash" style="font-size: 48px; color: #cbd5e0; margin-bottom: 15px; display: block;"></i>
                                        <p style="color: #9ca3af; font-weight: 600;">No customers found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($data->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
