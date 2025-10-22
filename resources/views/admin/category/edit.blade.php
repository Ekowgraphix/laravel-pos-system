@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-edit"></i>
                Edit Category
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-info-circle"></i> Update category information
            </p>
        </div>

        <!-- Edit Category Form Card -->
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-10">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="card-title-modern">
                            <i class="fas fa-tag"></i>
                            Category Information
                        </h5>
                        <span class="badge-primary-modern badge-modern">
                            ID: #{{ $data->id }}
                        </span>
                    </div>

                    <form action="{{ route('categoryUpdate') }}" method="POST">
                        @csrf
                        <div class="card-body-modern">
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert-modern alert-success-modern">
                                    <i class="fas fa-check-circle"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <input type="hidden" name="categoryID" value="{{ $data->id }}">

                            <!-- Category Name Field -->
                            <div class="form-group-modern">
                                <label for="category" class="form-label-modern">
                                    <i class="fas fa-tag"></i> Category Name *
                                </label>
                                <input type="text" 
                                       name="category" 
                                       id="category"
                                       value="{{ old('category', $data->name) }}"
                                       class="form-control-modern @error('category') is-invalid @enderror" 
                                       placeholder="Enter category name">
                                @error('category')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Category Info -->
                            <div class="alert-modern alert-info-modern">
                                <i class="fas fa-info-circle"></i>
                                <strong>Created:</strong> {{ $data->created_at->format('F j, Y') }} |
                                <strong>Last Updated:</strong> {{ $data->updated_at->format('F j, Y') }}
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <button type="submit" class="btn-warning-modern btn-modern w-100">
                                        <i class="fas fa-save"></i>
                                        Update Category
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('categoryList') }}" class="btn-dark-modern btn-modern w-100">
                                        <i class="fas fa-arrow-left"></i>
                                        Back to List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
