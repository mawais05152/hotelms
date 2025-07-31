@extends('layouts.master')

@section('content')
<style>
    .star-rating .star {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
}
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
                    <h5>Edit Customer Feedback</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer-feedback.update', $feedback->id) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="form-group mb-3">
                            <label>Order</label>
                            <select name="order_id" class="form-control" required>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}" {{ $feedback->order_id == $order->id ? 'selected' : '' }}>
                                        {{ $order->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Feedback</label>
                            <textarea name="feedback_text" class="form-control">{{ $feedback->feedback_text }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-group mb-3">
                                <label>Rating</label>
                               <input type="hidden" name="rating" id="ratingInput" value="{{ $feedback->rating }}" required>
                                <div class="star-rating">
                                    <span class="star" data-value="1">&#9733;</span>
                                    <span class="star" data-value="2">&#9733;</span>
                                    <span class="star" data-value="3">&#9733;</span>
                                    <span class="star" data-value="4">&#9733;</span>
                                    <span class="star" data-value="5">&#9733;</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('customer-feedback.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
$(document).ready(function() {
    // ✅ 1. Pre-select stars on page load based on saved rating
    var currentRating = $('#ratingInput').val();
    $('.star').each(function() {
        if ($(this).data('value') <= currentRating) {
            $(this).addClass('selected');
        }
    });

    // ✅ 2. Handle star click to update rating
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


