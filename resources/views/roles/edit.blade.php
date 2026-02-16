@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">{{ isset($role) ? 'Edit Role' : 'Create New Role' }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST">
                    @csrf
                    @if(isset($role)) @method('PUT') @endif

                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Role Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $role->name ?? '') }}" placeholder="e.g. Manager" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Permissions</label>
                        <div class="row g-3">
                            @foreach($permissions as $permission)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" 
                                           value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                           {{ (isset($rolePermissions) && in_array($permission->id, $rolePermissions)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Save Role
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-light px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
