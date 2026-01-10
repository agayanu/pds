<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showFormLogin()
    {
        if (Auth::check()) { 
            return redirect()->route('home');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|string',
            'password' => 'required|string'
        ];
  
        $messages = [
            'username.required' => 'Username wajib diisi',
            'username.string'   => 'Username harus berupa string',
            'password.required' => 'Password wajib diisi',
            'password.string'   => 'Password harus berupa string'
        ];
  
        $validator = Validator::make($request->all(), $rules, $messages);
  
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $username = $request->input('username');
        $pass = $request->input('password');

        $check = DB::table('users')->where('username', $username)->whereNotNull('deleted_at')->count();
        if($check) {
            Session::flash('error', 'Username atau password salah');
            return redirect()->route('login');
        }
  
        $data = [
            'username' => $username,
            'password' => $pass,
        ];
  
        Auth::attempt($data, $request->get('remember'));
  
        if (Auth::check()) {
            return redirect()->route('home');
        } else {
            Session::flash('error', 'Username atau password salah');
            return redirect()->route('login');
        }
    }
  
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
