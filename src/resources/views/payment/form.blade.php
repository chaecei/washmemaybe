@extends('services')

@section('content')
<div class="container">
    <h2>Payment for Order #{{ $order->id }}</h2>

    <form action="{{ route('payment.store', $order->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="payment_method">Payment Method:</label>
            <input type="text" name="payment_method" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="amount">Amount:</label>
            <input type="number" name="amount" class="form-control" value="{{ $order->total_load * 150 }}" required>
        </div>

        <div class="mb-3">
            <label for="transaction_id">Transaction ID (optional):</label>
            <input type="text" name="transaction_id" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Submit Payment</button>
    </form>
</div>
@endsection
