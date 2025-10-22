@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-credit-card"></i>
                Payment Management
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-wallet"></i> Manage payment methods and accounts
            </p>
        </div>

        <div class="row">
            @if (auth()->user()->role == 'superadmin')
            <!-- Add Payment Card -->
            <div class="col-xl-4 col-lg-5">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="card-title-modern">
                            <i class="fas fa-plus-circle"></i>
                            Add New Payment
                        </h5>
                    </div>
                    <form action="{{ route('paymentCreate') }}" method="post">
                        @csrf
                        <div class="card-body-modern">
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert-modern alert-success-modern">
                                    <i class="fas fa-check-circle"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Payment Type -->
                            <div class="form-group-modern">
                                <label for="paymentType" class="form-label-modern">
                                    <i class="fas fa-credit-card"></i> Payment Type *
                                </label>
                                <select name="paymentType" 
                                        id="paymentType"
                                        class="form-control-modern @error('paymentType') is-invalid @enderror">
                                    <option value="">Choose payment method...</option>
                                    <option value="KBZPay">KBZ Pay</option>
                                    <option value="KBZ">KBZ Account</option>
                                    <option value="WPay">Wave Pay</option>
                                    <option value="YOMA">YOMA Account</option>
                                    <option value="AYA">AYA Account</option>
                                    <option value="AYAPay">AYA Pay</option>
                                    <option value="CB">CB Account</option>
                                    <option value="CBPay">CB Pay</option>
                                    <option value="APay">A Pay</option>
                                </select>
                                @error('paymentType')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Card Number -->
                            <div class="form-group-modern">
                                <label for="card_number" class="form-label-modern">
                                    <i class="fas fa-hashtag"></i> Account Number *
                                </label>
                                <input type="text" 
                                       name="card_number" 
                                       id="card_number"
                                       value="{{ old('card_number') }}"
                                       class="form-control-modern @error('card_number') is-invalid @enderror"
                                       placeholder="Enter account number">
                                @error('card_number')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Cardholder Name -->
                            <div class="form-group-modern">
                                <label for="cardholder_name" class="form-label-modern">
                                    <i class="fas fa-user"></i> Account Holder Name *
                                </label>
                                <input type="text" 
                                       name="cardholder_name" 
                                       id="cardholder_name"
                                       value="{{ old('cardholder_name') }}"
                                       class="form-control-modern @error('cardholder_name') is-invalid @enderror"
                                       placeholder="Enter account holder name">
                                @error('cardholder_name')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <button type="submit" class="btn-success-modern btn-modern w-100">
                                        <i class="fas fa-plus"></i>
                                        Add Payment
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('adminDashboard') }}" class="btn-dark-modern btn-modern w-100">
                                        <i class="fas fa-arrow-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Payment List Card -->
            <div class="{{ auth()->user()->role == 'superadmin' ? 'col-xl-8 col-lg-7' : 'col-12' }}">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="card-title-modern">
                            <i class="fas fa-list-alt"></i>
                            All Payment Methods
                        </h5>
                    </div>
                    <div class="card-body-modern">
                        <div class="table-modern-wrapper">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">ID</th>
                                        <th style="width: 25%;">Payment Type</th>
                                        <th style="width: 25%;">Account Number</th>
                                        <th style="width: 25%;">Account Holder</th>
                                        @if (auth()->user()->role == 'superadmin')
                                            <th style="width: 15%; text-align: center;">Actions</th>
                                        @endif
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
                                                <span class="badge-info-modern badge-modern">
                                                    <i class="fas fa-credit-card"></i>
                                                    {{ $item->type }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $item->account_number }}</strong>
                                            </td>
                                            <td>
                                                <i class="fas fa-user me-2"></i>
                                                {{ $item->account_name }}
                                            </td>
                                            @if (auth()->user()->role == 'superadmin')
                                            <td>
                                                <div class="action-buttons-modern" style="justify-content: center;">
                                                    <a href="{{ route('paymentEdit', $item->id) }}" 
                                                       class="action-btn-modern action-btn-edit"
                                                       title="Edit Payment">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('paymentDelete', $item->id) }}" 
                                                       class="action-btn-modern action-btn-delete"
                                                       onclick="return confirm('Are you sure you want to delete this payment method?')"
                                                       title="Delete Payment">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->role == 'superadmin' ? '5' : '4' }}" style="text-align: center; padding: 40px;">
                                                <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e0; margin-bottom: 15px; display: block;"></i>
                                                <p style="color: #9ca3af; font-weight: 600;">No payment methods found</p>
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
        </div>
    </div>
@endsection


