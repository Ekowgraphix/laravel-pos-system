@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-tags"></i>
                Category Management
            </h1>
            <p class="page-subtitle-modern">
                <i class="far fa-folder-open"></i> Manage your product categories
            </p>
        </div>

        <!-- Category List Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-list"></i>
                    All Categories
                </h5>
                <a href="{{ route('categoryCreatePage') }}" class="btn-primary-modern btn-modern">
                    <i class="fas fa-plus"></i>
                    Add Category
                </a>
            </div>
            <div class="card-body-modern">
                <div class="table-modern-wrapper">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th style="width: 40%;">Category Name</th>
                                <th style="width: 30%;">Created Date</th>
                                <th style="width: 20%; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>
                                        <span class="badge-primary-modern badge-modern">
                                            #{{ $item->id }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $item->name }}</strong>
                                    </td>
                                    <td>
                                        <i class="far fa-calendar-alt me-2"></i>
                                        {{ $item->created_at->format('F j, Y') }}
                                    </td>
                                    <td>
                                        <div class="action-buttons-modern" style="justify-content: center;">
                                            <a href="{{ route('categoryEdit', $item->id) }}" 
                                               class="action-btn-modern action-btn-edit"
                                               title="Edit Category">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('categoryDelete', $item->id) }}" 
                                               class="action-btn-modern action-btn-delete"
                                               onclick="return confirm('Are you sure you want to delete this category?')"
                                               title="Delete Category">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e0; margin-bottom: 15px; display: block;"></i>
                                        <p style="color: #9ca3af; font-weight: 600;">No categories found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($data->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
