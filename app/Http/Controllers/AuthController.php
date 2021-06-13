<?php

namespace App\Http\Controllers;

use App\Models\Logging;
use DateTime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Function for user registration
    public function register (Request $request) {
        // First, the input fields are passed through validation. All fields are required, the username must be unique, the description can't be over 255 characters, the password must be at least 6 characters and there must be a password_confirmation field in the request that matches the password. If these requirements are not met, appropriate messages are sent to the register page.
        $fields = $request->validate([
            'username' => 'required|unique:users',
            'description' => 'required|max:255',
            'password' => 'required|min:6|confirmed'
        ]);
        
        // If validation is successful, we use the User model to create a new record in the users table. The make function of the Hash facade passes the password through bcrypt encryption. The default options for how many rounds the value is hashed is 10, but we can configure the rounds option to increase the time it takes for the password to be hashed.
        $user = User::create([
            'username' => $fields['username'],
            'password' => Hash::make($fields['password'], [
                'rounds' => 12
            ]),
            'description' => $fields['description']
        ]);
        
        if($user) {
            return redirect()->route('login')->with('message', 'User registered successfully.');
        } else {
            return back()->with('message', 'Registration error occured.');
        }
    }

    // Function for user login
    public function login (Request $request) {
        // The input fields are passed through validation. If the username and password are not submitted, appropriate messages are sent to the login/home page.
        $fields = $request->validate([
            'username' => 'required',
            'password' => 'required|alpha_num|min:6'
        ]);
        
        // The Auth facade provides us with a handy function called attempt, which accepts the submitted credentials and follows a certain process: it accepts an array of credentials, in our case username and password, and searches the users table to see if there is a user stored with such a username. If it finds one, it checks to see whether the submitted password is correct. Remember, the password stored in the users table is a hash, so the attempt function checks it using our encryption algorithm. If the two passwords match, then an authenticated session will be started for the user and true is returned.
        if (Auth::attempt($fields)) {
            // check if password was updated in the last 3 months
            if(now()->diffInMonths(auth()->user()->password_updated_at) >= 3) {
                return back()->with('message', 'You need to reset your password.');
            }
            // The session is regenerated to prevent session fixation.
            $request->session()->regenerate();

            Logging::create([
                'user_id' => auth()->user()->id,
                'success_at' => now(),
            ]);

            return redirect()->route('helloworld');
        }

        return back()->with('message', 'Bad credentials.');
    }

    // Function that handles logging out
    public function logout (Request $request) {
        // The Auth facade provides the logout function, which erases the authentication information from the user session
        Auth::logout();

        // The session is invalidated and the CSRF token is regenerated
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
