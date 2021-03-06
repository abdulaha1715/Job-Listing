<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Construct function
     */
    public function __construct()
    {
        $this->middleware('guest')->only(['create','login']);
    }
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.register');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'min:3'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password)
            ]);

            // Login
            auth()->login($user);

            return redirect('/')->with('message', 'User created and logged in');
        } catch (\Throwable $th) {
            return redirect('/')->with('message', $th->getMessage());
        }
    }

    /**
     * Show the login form for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('users.login');
    }

    /**
     * User Log In Authentication.
     *
     * @return \Illuminate\Http\Response
     */
    public function authentication(Request $request)
    {
        $authLogin = $request->validate([
            'email'    => ['required', 'email'],
            'password' => 'required',
        ]);

        if(auth()->attempt($authLogin)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }

    /**
     * Log User Out.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');
    }
}
