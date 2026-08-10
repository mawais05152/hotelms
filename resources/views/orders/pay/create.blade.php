@extends('layouts.master')
    @section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-4">Create Payment</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.pay.store', $order->id) }}" method="POST" id="payment-form">
                            <input type="hidden" name="amount" value="{{ $order->amount }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="card-holder-name">Card Holder Name</label>
                                <input id="card-holder-name" type="text" class="form-control" name="card_holder_name" placeholder="Card Holder Name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="card-element">Credit or debit card</label>
                                <div id="card-element" class="form-control" style="padding: 10px;"></div>
                            </div>
                            <div class="d-flex justify-content-end mt-3 gap-2">
                                <button type="button" id="card-button" class="btn btn-sm btn-primary">SUBMIT</button>
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');
        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');

        cardButton.addEventListener('click', async (e) => {
            e.preventDefault();
            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: cardHolderName.value,
                },
            });

            if (error) {
                alert(error.message);
            } else {
                const form = document.getElementById('payment-form');
                const paymentMethodInput = document.createElement('input');
                paymentMethodInput.type = 'hidden';
                paymentMethodInput.name = 'payment_method';
                paymentMethodInput.value = paymentMethod.id;
                const stripeTokenInput = document.createElement('input');
                stripeTokenInput.type = 'hidden';
                stripeTokenInput.name = 'stripeToken';
                stripeTokenInput.value = 'pm_test';
                form.appendChild(paymentMethodInput);
                form.appendChild(stripeTokenInput);
                form.submit();
            }
        });
    </script>
@endsection
