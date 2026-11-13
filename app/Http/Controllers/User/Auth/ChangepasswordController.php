<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Hash, Auth;
use App\Models\Customer;

class ChangepasswordController extends Controller
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



    public function changepassword(Request $request)
    {
        if ($request->current_password != null){
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',

            ]);

            if($request->current_password == $request->password){

                return back()->with('error', 'Votre nouveau mot de passe doit être différent de l’ancien. Choisissez-en un autre.');

            }else{
                $user = Auth::guard('customer')->user();
                if (Hash::check($request->current_password, $user->password)) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    Auth::guard('customer')->logout();

                    return  redirect()->route('auth.login')->with('success', 'Mot de passe modifié. Reconnectez-vous avec le nouveau.');
                }
                else{

                    return back()->with('error', 'Le mot de passe actuel est incorrect.');
                }
            }
        }else{
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',

            ]);
            if ($request->password == $request->password_confirmation){
                $user = Auth::guard('customer')->user();
                $user->password = Hash::make($request->password);
                $user->save();
                Auth::guard('customer')->logout();

                return  redirect()->route('auth.login')->with('success', 'Mot de passe enregistré. Reconnectez-vous avec le nouveau.');
            }
            else{
                return back()->with('error', 'Le mot de passe et sa confirmation ne correspondent pas.');
            }
        }
    }
}
