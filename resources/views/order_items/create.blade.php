@extends('layouts.master')
@section('content')

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4>Add Order Item</h4>
    </div>

    <div class="card-body">
        <input type="hidden" id="orderIdField" value="{{ $order_id }}">



        <button class="btn btn-success" id="saveItemBtn">Save Item</button>
    </div>
</div>

<!-- Required Scripts -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$(function(){

    $('.select2').select2({ width: '100%' });

    $('#categorySelect').change(function(){
        let catId = $(this).val();
        $('#productSelect').html('<option value="">Select Product</option>').prop('disabled', true);

        if(catId){
            $.get('/products/by-category/' + catId, function(res){

                res.forEach(p => {
                    $('#productSelect').append(`<option value="${p.id}" data-price="${p.price}">${p.name}</option>`);
                });

                $('#productSelect').prop('disabled', false);
                $('#productSelect').select2('destroy').select2({ width: '100%' });
            });
        }
    });

    $('#productSelect').change(function(){
        let price = $(this).find(':selected').data('price') || 0;
        $('#priceField').val(price);
        calculateSubtotal();
    });

    $('#quantityField').on('input', function(){
        calculateSubtotal();
    });

    function calculateSubtotal(){
        let price = parseFloat($('#priceField').val()) || 0;
        let qty = parseInt($('#quantityField').val()) || 0;
        $('#subTotalField').val(price * qty);
    }

    $('#saveItemBtn').click(function(){
        $.ajax({
            url: '/order-items',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: $('#orderIdField').val(),
                product_id: $('#productSelect').val(),
                price: $('#priceField').val(),
                quantity: $('#quantityField').val()
            },
            success: function(){
                alert('Item Added Successfully');
                location.reload();
            },
            error: function(xhr){
                alert('Error: ' + xhr.responseText);
            }
        });
    });

});
</script>

@endsection
