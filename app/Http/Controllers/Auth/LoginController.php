<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

//use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function login(){
        return view('auth/login');
    }

    public function authenticate(Request $request)
    {
        //Buscar el usuario admin en db
        $u_admin = User::all()->first();

        //validar credenciales ingresadas
        $credentials = $request->validate([
            'password' => ['required'],
        ]);
        $credentials = $credentials + ['email'=>$u_admin->email];
        
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
        
    }
}