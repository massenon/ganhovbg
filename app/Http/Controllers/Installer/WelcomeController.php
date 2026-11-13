<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){
        if (!preg_match('/^localhost$/', request()->getHost())) {
            return view('installer.welcome');
        }
    }
}
