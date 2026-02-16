@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Roles Management</h5>
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm px-4">
                    <i class="fas fa-plus me-1"></i> Add Role
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge bg-info text-dark">{{ $role->name }}</span></td>
                                <td>
                                    @foreach($role->permissions as $permission)
                                        <span class="badge bg-light text-primary border">{{ $permission->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Delete this role?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
