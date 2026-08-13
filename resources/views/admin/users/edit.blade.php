@extends('admin.layouts.master')

@push('title') Edit User - {{ $editingUser->name }} @endpush

@section('content')
<div class="content-wrapper">
    <div class="page-heading mb-4 d-flex justify-content-between align-items-center">
        <div>
            <span class="eyebrow text-warning">USER MANAGEMENT</span>
            <h1>Edit <span>User</span></h1>
            <p class="text-muted">Update member account information and credentials.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm" style="border-radius: 10px;">
            <i class="mdi mdi-arrow-left"></i> Back to Users
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card gold-card" style="background: #12151c; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 14px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                <span class="p-2 rounded bg-dark text-warning border border-warning me-3" style="font-size: 1.25rem;"><i class="mdi mdi-account-edit"></i></span>
                <div>
                    <h4 class="card-title text-white mb-1">Update Member Details</h4>
                    <p class="text-muted small mb-0">Modify information for {{ $editingUser->name }}.</p>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $editingUser) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                @include('admin.users._form', ['editingUser' => $editingUser])

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary" style="border-color: rgba(245, 185, 27, 0.15) !important;">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">Cancel</a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4" style="border-radius: 10px;">
                        <i class="mdi mdi-content-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
