@extends('layouts.master')
@section('content')

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h4>Products</h4>
        <button class="btn btn-success" id="addProductBtn">+ Add Product</button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '' }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning editProductBtn"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-category="{{ $product->category_id }}"
                            data-price="{{ $product->price }}">Edit</button>

                        <form action="{{ url('/products/'.$product->id) }}" method="POST" style="display:inline-block;">
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
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/products') }}" method="POST" id="productForm">
                @csrf
                <input type="hidden" id="product_id" name="id">

                <div class="modal-header">
                    <h5 class="modal-title">Add / Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Product</button>
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

    $('#addProductBtn').click(function(){
        $('#productForm')[0].reset();
        $('#product_id').val('');
        $('#productForm').attr('action', '/products');
        $('#productForm input[name="_method"]').remove();
        $('#productModal').modal('show');
    });

    $('.editProductBtn').click(function(){
        let id = $(this).data('id');
        let name = $(this).data('name');
        let category = $(this).data('category');
        let price = $(this).data('price');

        $('#product_id').val(id);
        $('input[name="name"]').val(name);
        $('input[name="price"]').val(price);
        $('select[name="category_id"]').val(category);

        $('#productForm').attr('action', '/products/' + id);

        if($('#productForm input[name="_method"]').length == 0){
            $('#productForm').append('<input type="hidden" name="_method" value="PUT">');
        } else {
            $('#productForm input[name="_method"]').val('PUT');
        }

        $('#productModal').modal('show');
    });

});
</script>

@endsection
