<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // dd('hi');
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();
            // dd($finduser);
            if ($finduser) {
                Auth::login($finduser);
                // dd($a);
                return redirect('/home');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt('12345678')
                ]);
                Auth::login($newUser);
                return redirect('/home');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        // dd('hi');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
