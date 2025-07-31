@extends('layouts.master')
@section('content')
<div class="card">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h4>Restaurant Tables</h4>
        <button class="btn btn-success" id="addTableBtn">+ Add Table</button>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Table Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookingTables as $table)
                <tr>
                    <td>{{ $table->id }}</td>
                    <td>{{ $table->table_number }}</td>
                    <td>{{ $table->status }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning editTableBtn"
                            data-id="{{ $table->id }}"
                            data-number="{{ $table->table_number }}"
                            data-status="{{ $table->status }}">Edit</button>

                        <form action="{{ url('/bookingtables/'.$table->id) }}" method="POST" style="display:inline-block;">
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

<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/bookingtables') }}" method="POST" id="tableForm">
                @csrf
                <input type="hidden" name="id" id="table_id"  name="_method">

                <div class="modal-header">
                    <h5 class="modal-title">Add / Edit Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Table Number</label>
                        <input type="text" name="table_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select Status</option>
                            <option value="Available">Available</option>
                            <option value="Occupied">Occupied</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Table</button>
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

    $('#addTableBtn').click(function(){
        $('#tableForm')[0].reset();
        $('#table_id').val('');
        $('#tableForm').attr('action', '/bookingtables');
        $('#tableForm input[name="_method"]').remove();
        $('#tableModal').modal('show');
    });

    $('.editTableBtn').click(function(){
        let id = $(this).data('id');
        let number = $(this).data('number');
        let status = $(this).data('status');

        $('#table_id').val(id);
        $('input[name="table_number"]').val(number);
        $('select[name="status"]').val(status);

        $('#tableForm').attr('action', '/bookingtables/' + id);

        if($('#tableForm input[name="_method"]').length == 0){
            $('#tableForm').append('<input type="hidden" name="_method" value="PUT">');
        } else {
            $('#tableForm input[name="_method"]').val('PUT');
        }

        $('#tableModal').modal('show');
    });

});
</script>
@endsection
