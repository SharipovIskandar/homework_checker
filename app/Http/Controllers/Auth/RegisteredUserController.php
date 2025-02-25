<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname'  => ['required', 'string'],
            'phone'     => ['required', 'string', 'unique:users,phone'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user = User::create([
            'fullname'  => $request->fullname,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if(auth()->user()->role == 'admin')
            return redirect()->route('admin.dashboard');
        else 
            return redirect()->route('home');
    }
}
