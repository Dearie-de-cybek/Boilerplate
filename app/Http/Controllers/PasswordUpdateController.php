<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordUpdateController extends Controller
{
     /**
     * Display the password reset form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // public function showResetForm(Request $request, $token = null)
    // {
    //     return view('auth.passwords.reset')->with([
    //         'token' => $token,
    //         'email' => $request->email
    //     ]);
    // }

    /**
     * Handle the password reset request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('status', 'Password updated successfully');
    }
}
