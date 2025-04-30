@extends('dashboard')

@section('dynamic-cont')
  <div class="pending-content">
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
          <td><span class="badge-processing">Processing</span></td>
      </tr>
      <tr>
          <td>WIN008</td>
          <td>Pending</td>
          <td>150.00</td>
          <td><span class="badge-processing">Processing</span></td>
      </tr>
      <tr>
          <td>WIN009</td>
          <td>Pending</td>
          <td>300.00</td>
          <td><span class="badge-processing">Processing</span></td>
      </tr>
      <tr>
          <td>WIN010</td>
          <td>Pending</td>
          <td>250.00</td>
          <td><span class="badge-processing">Processing</span></td>
      </tr>
      <tr>
          <td>WIN011</td>
          <td>Pending</td>
          <td>600.00</td>
          <td><span class="badge-processing">Processing</span></td>
      </tr>
      <tr>
          <td>WIN012</td>
          <td>Pending</td>
          <td>450.00</td>
          <td><span class="badge-processing">Processing</span></td>
      </tr>
      </tbody>
  </table>
  </div>

  <!-- Second Table: Additional Notes -->
<div class="table-container">
  <table class="table table-bordered notes-table">
      <thead>
      <tr>
          <th>Service Number</th>
          <th>Additional Notes</th>
      </tr>
      </thead>
      <tbody>
      <tr>
          <td>WIN001</td>
          <td>I already separated my loads by color.</td>
      </tr>
      <tr>
          <td>WIN005</td>
          <td>Use two additional detergents and fabric softener for all my load.</td>
      </tr>
      <tr>
          <td>WIN008</td>
          <td>No additional notes.</td>
      </tr>
      <tr>
          <td>WIN011</td>
          <td>Don't separate the colors. And add an additional fabric softener for all my load.</td>
      </tr>
      </tbody>
  </table>
  </div>
@endsection