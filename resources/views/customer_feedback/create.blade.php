@extends('layouts.master')

@section('content')
<style>
    .star-rating .star {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
    }
    .star-rating .star.selected,
    .star-rating .star:hover,
    .star-rating .star:hover ~ .star {
        color: gold;
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Add Customer Feedback</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer-feedback.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Order</label>
                            <select name="order_id" class="form-control" required>
                                <option value="">Select Order</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}">{{ $order->id }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Feedback</label>
                            <textarea name="feedback_text" class="form-control"></textarea>
                        </div>

                        {{-- <div class="form-group mb-3">
                            <label>Rating</label>
                            <select name="rating" class="form-control" required>
                                <option value="Good">Good</option>
                                <option value="Bad">Bad</option>
                                <option value="Neutral">Neutral</option>
                            </select>
                        </div> --}}
                        <div class="form-group mb-3">
                            <div class="form-group mb-3">
                                <label>Rating</label>
                               <input type="hidden" name="rating" id="ratingInput" required>
                                <div class="star-rating">
                                    <span class="star" data-value="1">&#9733;</span>
                                    <span class="star" data-value="2">&#9733;</span>
                                    <span class="star" data-value="3">&#9733;</span>
                                    <span class="star" data-value="4">&#9733;</span>
                                    <span class="star" data-value="5">&#9733;</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('customer-feedback.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
    $('.star').click(function() {
        var selectedRating = $(this).data('value');
        var currentRating = $('#ratingInput').val();

        if (selectedRating == currentRating) {
            // Same star clicked again, reset rating
            $('#ratingInput').val('');
            $('.star').removeClass('selected');
        } else {
            // New rating selected
            $('#ratingInput').val(selectedRating);
            $('.star').removeClass('selected');
            $(this).prevAll().addBack().addClass('selected');
        }
    });
});

</script>
