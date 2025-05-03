<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
        // Show the Account Settings Page
    public function showSettings ()
    {
        $activePage = 'account';
        return view('account', compact('activePage')); //this will show the blade page
    }

    // Update Account Information
    public function updateInfo (Request $request)
    {
        $user = Auth::user(); //get the current logged in admin

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'profile_pictures' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'
            // 'status' => 'required',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        // $user->status = $request->status;

        //If a profile picture was uploaded
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename =  time() . '.' . $file-> getClientOriginalExtension();
            $file->move(public_path('profile_pictures'), $filename);
            $user->profile_pictures = $filename;
        }
        
        $user->save();

        return back()->with('success', 'Account Information updated successfully!');    
    }

    //Change password
    public function changePassword(Request $request)
    {
        $user = Auth::user(); //get current user

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8', //new password and new_password_confirmation must match
        ]);

        //Check if old password is correct
        if(!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Old password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->last_password_change = now(); //update the date when password was last changed
        $user->save();

        return back()->with('success', 'Password changed succesfully!');
    }
}
