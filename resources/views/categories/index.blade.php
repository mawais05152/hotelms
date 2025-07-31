@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h4 class="mb-0">Menu Categories</h4>
        <button class="btn btn-success" id="addCategoryBtn">+ Add Category</button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning editCategoryBtn"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}">Edit</button>

                        <form action="{{ url('/categories/'.$category->id) }}" method="POST" style="display:inline-block;">
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

<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/categories') }}" method="POST" id="categoryForm">
                @csrf
                <input type="hidden" name="id" id="category_id">

                <div class="modal-header">
                    <h5 class="modal-title">Add / Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){

    $('#addCategoryBtn').click(function(){
        $('#categoryForm')[0].reset();
        $('#category_id').val('');
        $('#categoryForm').attr('action', '/categories');
        $('#categoryForm input[name="_method"]').remove();
        $('#categoryModal').modal('show');
    });

    $('.editCategoryBtn').click(function(){
        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#category_id').val(id);
        $('input[name="name"]').val(name);

        $('#categoryForm').attr('action', '/categories/' + id);

        if($('#categoryForm input[name="_method"]').length == 0){
            $('#categoryForm').append('<input type="hidden" name="_method" value="PUT">');
        } else {
            $('#categoryForm input[name="_method"]').val('PUT');
        }

        $('#categoryModal').modal('show');
    });

});
</script>
@endsection
