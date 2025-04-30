@extends('layouts.account')

@section('content')
    <!-- Profile Icon -->
    <div class="text-center">
        <i class="bi bi-person-circle profile-icon"></i>
    </div>

    <!-- Account Information -->
    <form action="{{ route('account.updateInfo') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" class="form-control" name="first_name" placeholder="First Name">
        <input type="text" class="form-control" name="last_name" placeholder="Last Name">
        <input type="text" class="form-control" name="mobile_number" placeholder="Mobile Number">
        <input type="email" class="form-control" name="email" placeholder="Email Address">
        <input type="text" class="form-control" name="username" placeholder="Username">
        
        <label for="profilePicture" class="form-label">Upload Profile Picture</label>
        <input type="file" class="form-control" id="profilePicture" name="profile_picture">
        
        <button type="submit" class="btn btn-custom mt-3">Confirm</button>

        <div class="section-title">Other Information</div>
        <select class="form-select" name="status">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        <input type="text" class="form-control" placeholder="Last Password Change Date" readonly>
    </form>

    <!-- Password Change -->
    <div class="section-title mt-4">Account Settings</div>
    <div class="subsection-title">Password Change</div>
    <form action="{{ route('account.changePassword') }}" method="POST">
        @csrf
        <input type="password" class="form-control" name="old_password" placeholder="Enter Old Password">
        <input type="password" class="form-control" name="new_password" placeholder="Enter New Password">
        <input type="password" class="form-control" name="new_password_confirmation" placeholder="Re-enter New Password">
        <button type="submit" class="btn btn-custom mt-3">Update Password</button>
    </form>
@endsection
