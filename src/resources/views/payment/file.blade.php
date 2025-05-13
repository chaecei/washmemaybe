@extends('services')

@section('content')
@if(isset($order))
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Payment for Order #{{ $order->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('payment.show', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method:</label>
                            <input type="text" name="payment_method" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount:</label>
                            <input type="number" name="amount" class="form-control" value="{{ $order->total_load * 150 }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="transaction_id" class="form-label">Transaction ID:</label>
                            <input type="text" name="transaction_id" class="form-control" required>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Submit Payment</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <p>Order not found.</p>
@endif

@@endsection