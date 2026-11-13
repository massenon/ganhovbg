<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Hash, Auth;
use App\Models\Customer;

class ChangePasswordController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('user.auth.passwords.changepassword');
    }



    public function changePassword(Request $request)
    {

        $request->validate([
          'current_password' => 'required|max:255',
          'password' => 'required|string|min:8|max:255|confirmed',
          'password_confirmation' => 'required|max:255',
        ]);

        $user = Auth::guard('customer')->user();
        
        if (Hash::check($request->current_password, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();

            Auth::guard('customer')->logout();
            $request->session()->flush();

            return  redirect()->route('auth.login')->with('success', lang('The password has been successfully changed!', 'alerts'));
        }
        else{
            return back()->with('error', lang('The current password does not match!', 'alerts'));
        }


    }
}
