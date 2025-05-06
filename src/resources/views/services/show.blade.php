<!-- resources/views/services/show.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Order Details</h2>
    <p><strong>Service Number:</strong> {{ $order->id }}</p>
    <p><strong>Status:</strong> {{ $order->category->name ?? 'Unknown' }}</p>
    <p><strong>Total Load:</strong> {{ $order->total_load }} kg</p>
    <p><strong>Service Type:</strong> {{ $order->service_type }}</p>
    <p><strong>Grand Total:</strong> ₱{{ number_format($order->total_load * 50, 2) }}</p> <!-- Assuming ₱50 per load -->

    <!-- Button to trigger the payment modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paymentModal">
        Proceed to Payment
    </button>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Payment Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('payment.store', ['order' => $order->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Online Transfer">Online Transfer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="text" name="amount" id="amount" class="form-control" value="₱{{ number_format($order->total_load * 50, 2) }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="transaction_id">Transaction ID (Optional)</label>
                        <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Enter transaction ID if available">
                    </div>

                    <button type="submit" class="btn btn-success">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
