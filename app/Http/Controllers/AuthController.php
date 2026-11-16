<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
 public function loginForm(){return view('auth.login');}
 public function registerForm(){return view('auth.register');}
 public function register(Request $request){
  $data=$request->validate(['name'=>'required|string|max:100','email'=>'required|email|max:255|unique:users,email','password'=>'required|string|min:8|confirmed']);
  $data['role']='customer';$user=User::create($data);Auth::login($user);$request->session()->regenerate();return redirect()->route('home')->with('success','Registrasi berhasil.');
 }
 public function login(Request $request){
  $data=$request->validate(['email'=>'required|email','password'=>'required']);
  if(!Auth::attempt($data)){return back()->withErrors(['email'=>'Email atau kata sandi salah.'])->onlyInput('email');}
  $request->session()->regenerate();return redirect()->intended(route(Auth::user()->role === 'admin' ? 'admin.home' : 'home'))->with('success','Login berhasil.');
 }
 public function logout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('home')->with('success','Logout berhasil.');}
}
