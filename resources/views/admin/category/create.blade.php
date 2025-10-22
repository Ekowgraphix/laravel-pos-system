@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-plus-circle"></i>
                Create New Category
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-info-circle"></i> Add a new product category to your system
            </p>
        </div>

        <!-- Create Category Form Card -->
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-10">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="card-title-modern">
                            <i class="fas fa-tag"></i>
                            Category Information
                        </h5>
                    </div>

                    <form action="{{ route('categoryCreate') }}" method="POST">
                        @csrf
                        <div class="card-body-modern">
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert-modern alert-success-modern">
                                    <i class="fas fa-check-circle"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Category Name Field -->
                            <div class="form-group-modern">
                                <label for="category" class="form-label-modern">
                                    <i class="fas fa-tag"></i> Category Name *
                                </label>
                                <input type="text" 
                                       name="category" 
                                       id="category"
                                       value="{{ old('category') }}"
                                       class="form-control-modern @error('category') is-invalid @enderror" 
                                       placeholder="Enter category name (e.g., Electronics, Clothing)">
                                @error('category')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <button type="submit" class="btn-success-modern btn-modern w-100">
                                        <i class="fas fa-check"></i>
                                        Create Category
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
