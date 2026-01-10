<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        if(!in_array(Auth::user()->role, ['0','1'])) {
            Auth::logout();
            return redirect()->route('login');
        }

        $idUser = Auth::user()->id;
        $pass = null;
        $dbPass = DB::table('users')->select('password')->where('id',$idUser)->first();
        if(Hash::check('123456', $dbPass->password)) {
            $pass = 'Y';
        }
        
        return view('home',['pass' => $pass]);
    }

    public function customer_update_pass(Request $r)
    {
        $rules = [
            'password' => 'required|confirmed|string',
        ];
    
        $messages = [
            'password.required'  => 'Password wajib diisi',
            'password.confirmed' => 'Password harus sama dengan konfirmasi',
            'password.string'    => 'Password harus string',
        ];
  
        $validator = Validator::make($r->all(), $rules, $messages);

        if($validator->fails()){
            $errorMsg = $validator->errors();
            return redirect()->back()->with('errorx', $errorMsg);
        }

        $id = Auth::user()->id;
        $pass = $r->input('password');
        DB::table('users')->where('id', $id)->update([
            'password'   => Hash::make($pass),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Berhasil ganti password');
    }
}
