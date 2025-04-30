<!-- <div class="pending-content">
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
        <td>WIN007</td>
        <td>Pending</td>
        <td>240.00</td>
        <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Process</button></td>
    </tr>
    <tr>
        <td>WIN008</td>
        <td>Pending</td>
        <td>150.00</td>
        <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Process</button></td>
    </tr>
    <tr>
        <td>WIN009</td>
        <td>Pending</td>
        <td>300.00</td>
        <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Process</button></td>
    </tr>
    <tr>
        <td>WIN010</td>
        <td>Pending</td>
        <td>250.00</td>
        <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Process</button></td>
    </tr>
    <tr>
        <td>WIN011</td>
        <td>Pending</td>
        <td>600.00</td>
        <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Process</button></td>
    </tr>
    <tr>
        <td>WIN012</td>
        <td>Pending</td>
        <td>450.00</td>
        <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Process</button></td>
    </tr>
    </tbody>
</table>
</div> -->

<!-- Second Table: Additional Notes -->
<!-- <div class="table-container">
<table class="table table-bordered notes-table">
    <thead>
    <tr>
        <th>Service Number</th>
        <th>Additional Notes</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>WIN007</td>
        <td>Separated the loads by color.</td>
    </tr>
    <tr>
        <td>WIN008</td>
        <td>Use two additional detergents and fabric softener for all the load.</td>
    </tr>
    <tr>
        <td>WIN011</td>
        <td>Don't separate the colors. And add an additional fabric softener for all the load.</td>
    </tr>
    </tbody>
</table>
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
        @foreach ($pendingServices as $service)
            <tr>
                <td>{{ $service->service_number }}</td>
                <td>{{ $service->status }}</td>
                <td>{{ number_format($service->grand_total, 2) }}</td>
                <td>
                    <form action="{{ route('dashboard.process', $service->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="alert('Successful!')">Process</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
</table>

<table class="table table-bordered notes-table">
    <thead>
        <tr>
            <th>Service Number</th>
            <th>Additional Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pendingServices->whereNotNull('notes') as $service)
            <tr>
                <td>{{ $service->service_number }}</td>
                <td>{{ $service->notes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>    