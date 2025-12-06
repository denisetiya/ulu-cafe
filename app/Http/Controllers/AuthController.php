<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $this->mergeSessionCartToDb();
            return $this->authenticatedRedirect();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default role
        ]);

        Auth::login($user);
        $this->mergeSessionCartToDb();

        return $this->authenticatedRedirect();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Google Auth
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Disable SSL verification for local development to avoid cURL error 60
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new Client(['verify' => false]))
                ->user();
            
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user);
                $this->mergeSessionCartToDb();
            } else {
                // Check if user with email exists, then link account, otherwise create new
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar
                    ]);
                    Auth::login($existingUser);
                    $this->mergeSessionCartToDb();
                } else {
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => Hash::make(uniqid()), // Random password
                        'role' => 'customer', // Default role
                    ]);
                    Auth::login($newUser);
                    $this->mergeSessionCartToDb();
                }
            }

            return $this->authenticatedRedirect();

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    private function mergeSessionCartToDb()
    {
        if (Auth::check()) {
            $sessionCart = Session::get('cart', []);
            $user = Auth::user();

            foreach ($sessionCart as $productId => $item) {
                $cartItem = CartItem::where('user_id', $user->id)
                    ->where('product_id', $productId)
                    ->first();

                if ($cartItem) {
                    $cartItem->quantity += $item['quantity'];
                    $cartItem->save();
                } else {
                    CartItem::create([
                        'user_id' => $user->id,
                        'product_id' => $productId,
                        'quantity' => $item['quantity']
                    ]);
                }
            }
            
            // Optional: Clear session cart after merging
            Session::forget('cart');
        }
    }

    private function authenticatedRedirect()
    {
        $user = Auth::user();
        if ($user->role === 'cashier') {
            return redirect()->route('cashier.dashboard');
        } elseif ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }
        return redirect()->intended('/');
    }
}
