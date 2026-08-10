@extends('layouts.master')
@section('content')
    @php use Carbon\Carbon; @endphp
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Orders Management</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <input type="text" id="searchInput" class="form-control w-25" style="height: 38px;" placeholder="Search by  Product, Status...">
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Table</th>
                        <th>Person</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Category/Product</th>
                        <th>Variation</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ordersTbody">
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->bookingTable->table_number ?? 'N/A' }}</td>
                            <td>{{ $order->person }}</td>
                            <td>{{ $order->order_type }}</td>
                            <td>{{ $order->date }}</td>
                            <td>{{ $order->time }}</td>
                            <td>{{ $order->category ? $order->category->name : 'N/A' }} - {{ $order->product ? $order->product->name : 'N/A' }} </td>
                            <td>{{ $order->variation ? $order->variation->unit . ' ' . $order->variation->size : 'N/A' }}</td>
                            <td>{{ $order->sub_total }}</td>
                            <td>
                                <a href="#" class="update-status-btn badge rounded-pill px-3 py-2 text-decoration-none *:
                                    @if($order->status === 'pending') bg-warning text-dark
                                    @elseif($order->status === 'taken') bg-info
                                    @elseif($order->status === 'delivered') bg-primary
                                    @elseif($order->status === 'completed') bg-success
                                    @else bg-secondary
                                    @endif" data-order-id="{{ $order->id }}" data-status-url="{{ route('orders.status.store', $order->id) }}" data-status="{{ $order->status }}">
                                    <i class="bi bi-arrow-repeat me-1"></i> {{ ucfirst($order->status) }}
                                 </a>
                            </td>
                            <td>{{ $order->payment_status }}</td>
                            <td class="d-flex flex-wrap gap-1">
                                <a href="{{ url('order-status/index/' . $order->id) }}" class="btn btn-info btn-sm">History</a>
                                <a href="{{ url('orders-process/' . $order->id) }}" class="btn btn-primary btn-sm">Payment</a>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="statusForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="order_id" id="statusOrderId">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Update Order Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="taken">Taken</option>
                                <option value="delivered">Delivered</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="replaced">Replaced</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Delivered By</label>
                            <select name="delivered_by" class="form-select">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.update-status-btn').on('click', function(e) {
            e.preventDefault();
            var orderId = $(this).data('order-id');
            var statusUrl = $(this).data('status-url');
            var status = $(this).data('status');

            $('#statusOrderId').val(orderId);
            $('#statusForm').attr('action', statusUrl);
            $('select[name="status"]').val(status);

            $('#statusModal').modal('show');
        });
    });

    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            let searchValue = $(this).val().toLowerCase().trim();

            $('#ordersTbody tr').each(function() {
                let tds = $(this).find('td');

                let orderId = tds.eq(0).text().trim().toLowerCase();
                let tableNumber = tds.eq(1).text().trim().toLowerCase();
                if (searchValue === '') {
                    $(this).show();
                }else if (searchValue === orderId || searchValue === tableNumber) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endsection
