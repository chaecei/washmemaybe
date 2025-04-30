<!-- <div class="table-container">
    <div class="table-responsive">
        <table class="table table-bordered text-center">
        <thead class="table-secondary">
            <tr>
            <th>SERVICE NUMBER</th>
            <th>Status</th>
            <th>Grand Total</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>WIN004</td>
            <td>In Progress</td>
            <td>300.00</td>
            <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Ready for Pickup</button></td>
            </tr>
            <tr>
            <td>WIN005</td>
            <td>In Progress</td>
            <td>500.00</td>
            <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Ready for Pickup</button></td>
            </tr>
            <tr>
            <td>WIN006</td>
            <td>In Progress</td>
            <td>520.00</td>
            <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Ready for Pickup</button></td>
            </tr>
            <td>WIN007</td>
            <td>In Progress</td>
            <td>600.00</td>
            <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Ready for Pickup</button></td>
            </tr>
        </tbody>
        </table>
    </div>
</div> -->


<table class="table table-bordered text-center">
    <thead class="table-secondary">
        <tr>
            <th>SERVICE NUMBER</th>
            <th>Status</th>
            <th>Grand Total</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($processingServices as $service)
            <tr>
                <td>{{ $service->service_number }}</td>
                <td>{{ $service->status }}</td>
                <td>{{ number_format($service->grand_total, 2) }}</td>
                <td>
                    <form action="{{ route('dashboard.readyforpickup', $service->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-warning btn-sm">Ready for Pickup</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
