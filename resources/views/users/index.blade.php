@extends('layouts.master')
@section('content')

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Users List</h4>
        <button class="btn btn-success" id="addUserBtn">+ Add New User</button>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="usersTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary editUserBtn"
                                data-user='@json($user)'>Edit</button>

                            <form action="{{ url('/users/'.$user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/users') }}" method="POST" id="userForm">
                @csrf
                <input type="hidden" id="user_id" name="id">

                <div class="modal-header">
                    <h5 class="modal-title">Add / Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="Waiter">Waiter</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {

    // Modal Instance
    var userModal = new bootstrap.Modal(document.getElementById('userModal'));

    // Open Modal on Button Click
    $('#addUserBtn').click(function() {
        $('#userForm')[0].reset(); // Form reset
        $('#user_id').val('');     // ID empty for new user
        userModal.show();
    });

    $('.editUserBtn').click(function() {
    let user = $(this).data('user');
    $('#user_id').val(user.id);
    $('[name="name"]').val(user.name);
    $('[name="email"]').val(user.email);
    $('[name="role"]').val(user.role);
    userModal.show();
});

});
</script>



@endsection
