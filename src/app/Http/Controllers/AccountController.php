<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;

class AccountController extends Controller
{
    public function showSettings ()
    {
        $activePage = 'account';
        return view('account', compact('activePage')); //this will show the blade page
    }

    public function updateInfo (Request $request)
    {
        $user = Auth::user(); //get the current logged in admin

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'profile_pictures' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($user->email !== $request->email) {
            $user->email = $request->email;

            Notification::create([
                'type' => 'email_update',
                'message' => 'Email address was Updated.'
            ]);
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename =  time() . '.' . $file-> getClientOriginalExtension();
            $file->move(public_path('profile_pictures'), $filename);
            $user->profile_pictures = $filename;
       
            Notification::create([
                'type' => 'profile_update',
                'message' => 'Profile picture was Updated.'
            ]);
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
            return back()->withErrors(['old_password' => 'The provided password does not match your current password.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);  // Hash the new password before storing it
        $user->save();

        Notification::create([
            'type' => 'password_change',
            'message' => 'Admin updated the password.',
            'is_read' => 0,
        ]);

        return redirect()->route('account.settings')->with('success', 'Password successfully updated!');
    }
}
