<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('admin.login', [
            'error' => $request->session()->get('error')
        ]);
    }

    public function loginAction(Request $request)
    {
        $data = $request->only([
            'email',
            'password'
        ]);

        if (!Auth::attempt($data)) {
            $request->session()->flash('error', 'E-mail e/ou senha incorretos!');
            return redirect()->route('login');
        }

        return redirect('/admin');
    }

    public function register(Request $request)
    {
        return view('admin.register', [
            'error' => $request->session()->get('error')
        ]);
    }

    public function registerAction(Request $request)
    {
        $data = $request->only([
            'email',
            'password',
            'password_confirmation'
        ]);

        $hasEmail = User::where('email', $data['email'])->first();

        if ($hasEmail) {
            $request->session()->flash('error', 'E-mail já cadastrado!');
            return redirect()->route('register');
        }

        $newUser = new User();
        $newUser->email = $data['email'];
        
        if ($data['password'] == $data['password_confirmation']) {
            if (strlen($data['password']) >= 4) {
                $newUser->password = password_hash($data['password'], PASSWORD_DEFAULT);
            } else {
                $request->session()->flash('error', 'A senha deve conter no mínimo quatro caracteres');
                return redirect()->route('register');
            }
        } else {
            $request->session()->flash('error', 'As senhas não coincidem');
            return redirect()->route('register');
        }

        $newUser->save();

        Auth::login($newUser);
        return redirect('/admin');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
