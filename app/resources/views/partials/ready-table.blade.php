<!-- <div class="table-container">
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
          <td>WIN002</td>
          <td>Ready for Pickup</td>
          <td>150.00</td>
          <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Claimed</button></td>
        </tr>
        <tr>
          <td>WIN003</td>
          <td>Ready for Pickup</td>
          <td>600.00</td>
          <td><button class="btn btn-success btn-sm" onclick="alert('Successful!')">Claimed</button></td>
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
        @foreach($readyServices as $service)
            <tr>
                <td>{{ $service->service_number }}</td>
                <td>{{ $service->status }}</td>
                <td>{{ number_format($service->grand_total, 2) }}</td>
                <td>
                    <form action="{{ route('dashboard.pending', $service->id) }}" method="POST" style="display-inline;">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="Processing...">Claimed</button>
                    </form>
<!-- 
                  <form action="{{ route('dashboard.unclaimed', $service->id) }}" method="POST" style="display:inline; margin-left: 5px;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Unclaimed</button> -->
                  </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
