<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        
        if (Auth::attempt([
            'tipo_documento' => $request->tipo_documento,
            'documento' => $request->documento,
            'password' => $request->password,
            'estado' => 1
        ])) {
            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['documento' => trans('auth.failed')])
            ->withInput(request(['documento']));
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|string',
            'documento' => 'required|string|min:8|max:20',
            'password' => 'required|string|min:8|max:20',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }
}
