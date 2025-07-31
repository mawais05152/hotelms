@extends('layouts.master')
@section('content')

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h4>Payments</h4>
        <button class="btn btn-success" id="addPaymentBtn">+ Add Payment</button>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Order ID</th>
                    {{-- <th>Order Items</th> --}}
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->order_id }}</td>
                    {{-- <td>{{ $payment->order_items_name }}</td> --}}
                    <td>{{ $payment->sab_price }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>
                        {{-- <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this payment?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Add Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label>Order ID</label>
            <input type="number" name="order_id" id="order_id" class="form-control" required placeholder="Enter Order ID">
        </div>

          <div class="mb-3">
            <label>Order Items</label>
            <textarea id="order_items" class="form-control" readonly rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label>Total Price</label>
            <input type="number" step="0.01" name="sab_price" id="sab_price" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" class="form-select" required>
              <option value="">Select Method</option>
              <option value="Cash">Cash</option>
              <option value="Card">Card</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
    $('#addPaymentBtn').click(function(){
        $('#paymentForm')[0].reset();
        $('#order_items').val('');
        $('#sab_price').val('');
        $('#paymentModal').modal('show');
    });

    $('#order_id').on('input', function(){
    var order_id = $(this).val();
    if(order_id) {
        $.ajax({
            url: '/payments/get-order-items/' + order_id,
            type: 'GET',
            success: function(data) {
                $('#order_items').val(data.order_items);
                $('#sab_price').val(data.total_price);
            },
            error: function() {
                $('#order_items').val('');
                $('#sab_price').val('');
                alert('Error fetching order items!');
            }
        });
    }
});

});
</script>
@endsection
