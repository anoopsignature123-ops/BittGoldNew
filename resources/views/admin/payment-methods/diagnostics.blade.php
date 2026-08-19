@extends('admin.layouts.master')

@push('title')
    Payment Methods Diagnostics
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-heading">
            <div>
                <span class="eyebrow">SYSTEM STATUS</span>
                <h1>Payment Method <span>Diagnostics</span></h1>
                <p>Check if payment method system is properly configured.</p>
            </div>
        </div>

        <div class="row">
            {{-- Database Status --}}
            <div class="col-lg-6 mb-4">
                <div class="card gold-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-database"></i> Database Status
                        </h5>

                        @php
                            use Illuminate\Support\Facades\Schema;
                            use Illuminate\Database\QueryException;
                            
                            $paymentMethodsExists = false;
                            $hasPaymentMethod = false;
                            $hasPaymentDetails = false;
                            $methodCount = 0;
                            
                            try {
                                $paymentMethodsExists = Schema::hasTable('payment_methods');
                            } catch (QueryException $e) {
                                $paymentMethodsExists = false;
                            }
                            
                            try {
                                if (Schema::hasTable('deposits')) {
                                    $hasPaymentMethod = Schema::hasColumn('deposits', 'payment_method');
                                    $hasPaymentDetails = Schema::hasColumn('deposits', 'payment_details');
                                }
                            } catch (QueryException $e) {
                                $hasPaymentMethod = false;
                                $hasPaymentDetails = false;
                            }
                            
                            if ($paymentMethodsExists) {
                                try {
                                    $methodCount = \App\Models\PaymentMethod::count();
                                } catch (\Exception $e) {
                                    $methodCount = 0;
                                }
                            }
                        @endphp

                        <div class="list-group list-group-flush">
                            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-2 text-white">
                                <span>Payment Methods Table</span>
                                <span class="badge {{ $paymentMethodsExists ? 'bg-success' : 'bg-danger' }}">
                                    {{ $paymentMethodsExists ? '✓ READY' : '✗ MISSING' }}
                                </span>
                            </div>

                            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-2 text-white">
                                <span>Deposits - payment_method Column</span>
                                <span class="badge {{ $hasPaymentMethod ? 'bg-success' : 'bg-danger' }}">
                                    {{ $hasPaymentMethod ? '✓ READY' : '✗ MISSING' }}
                                </span>
                            </div>

                            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-2 text-white">
                                <span>Deposits - payment_details Column</span>
                                <span class="badge {{ $hasPaymentDetails ? 'bg-success' : 'bg-danger' }}">
                                    {{ $hasPaymentDetails ? '✓ READY' : '✗ MISSING' }}
                                </span>
                            </div>

                            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-2 border-bottom text-white">
                                <span>Payment Methods in Database</span>
                                <span class="badge bg-info">{{ $methodCount }} Records</span>
                            </div>
                        </div>

                        @if (!$paymentMethodsExists || !$hasPaymentMethod || !$hasPaymentDetails)
                            <div class="alert alert-warning mt-3 mb-0">
                                <strong>Setup Required!</strong><br>
                                <small>Run the SQL setup or follow PAYMENT_SETUP_GUIDE.md</small>
                            </div>
                        @else
                            <div class="alert alert-success mt-3 mb-0">
                                <strong>✓ All Systems Ready!</strong><br>
                                <small>Database is properly configured</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payment Methods List --}}
            <div class="col-lg-6 mb-4">
                <div class="card gold-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-list-box"></i> Configured Methods
                        </h5>

                        @if ($paymentMethodsExists && $methodCount > 0)
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 text-white">
                                    <tbody>
                                        @foreach (\App\Models\PaymentMethod::orderBy('sort_order')->get() as $method)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-{{ $method->type === 'qr' ? 'warning' : ($method->type === 'upi' ? 'info' : 'success') }}">
                                                        {{ strtoupper($method->type) }}
                                                    </span>
                                                </td>
                                                <td><strong>{{ $method->title }}</strong></td>
                                                <td>
                                                    <span class="badge {{ $method->is_active ? 'bg-success' : 'bg-secondary' }} float-end">
                                                        {{ $method->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <small>No payment methods configured yet.<br>
                                Go to <a href="{{ route('admin.payment-methods.index') }}">Payment Methods</a> to add some.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- File Checks --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card gold-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-file-multiple"></i> Required Files
                        </h5>

                        @php
                            $files = [
                                'Controller' => app_path('Http/Controllers/Admin/PaymentMethodController.php'),
                                'Model' => app_path('Models/PaymentMethod.php'),
                                'Admin View' => resource_path('views/admin/payment-methods/index.blade.php'),
                                'User View' => resource_path('views/user/deposit/index.blade.php'),
                                'Migration 1' => database_path('migrations/2026_08_17_000001_create_payment_methods_table.php'),
                                'Migration 2' => database_path('migrations/2026_08_17_000002_add_payment_method_to_deposits_table.php'),
                            ];
                        @endphp

                        <div class="row">
                            @foreach ($files as $name => $path)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        @if (file_exists($path))
                                            <i class="mdi mdi-check-circle text-success me-2" style="font-size: 1.2rem;"></i>
                                            <span class="text-white"><strong>{{ $name }}</strong><br><small class="text-muted">{{ basename($path) }}</small></span>
                                        @else
                                            <i class="mdi mdi-alert-circle text-danger me-2" style="font-size: 1.2rem;"></i>
                                            <span class="text-white"><strong class="text-danger">{{ $name }}</strong><br><small class="text-muted">MISSING!</small></span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card gold-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-lightning-bolt"></i> Quick Actions
                        </h5>

                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-gold">
                                <i class="mdi mdi-credit-card-settings"></i> Manage Payment Methods
                            </a>
                            <a href="{{ route('user.deposit.index') }}" class="btn btn-outline-gold">
                                <i class="mdi mdi-wallet-plus"></i> Test User Deposit
                            </a>
                            <a href="{{ route('admin.deposits.index') }}" class="btn btn-outline-gold">
                                <i class="mdi mdi-file-document"></i> View Deposits
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection