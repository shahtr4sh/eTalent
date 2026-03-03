<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pemohon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show()
    {
        return view('app.auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower(trim($request->input('email')));
        $password = $request->input('password');

        // 1) Pastikan email wujud dalam table pemohon (robust: TRIM)
        $pemohon = Pemohon::query()
            ->whereRaw('LOWER(TRIM(emel_rasmi)) = ?', [$email])
            ->first();

        if (!$pemohon) {
            return back()->withErrors([
                'email' => 'Emel rasmi ini tidak wujud dalam rekod staf UniSHAMS.',
            ])->withInput();
        }

        if (blank($pemohon->staff_id)) {
            return back()->withErrors([
                'email' => 'Rekod staf dijumpai tetapi Staff ID tiada. Sila semak data pemohon.',
            ])->withInput();
        }

        // 2) Auto-provision / sync users
        $user = User::query()->whereRaw('LOWER(TRIM(email)) = ?', [$email])->first();

        if (!$user) {
            $user = User::create([
                'name' => $pemohon->nama,
                'email' => $email,
                'staff_id' => $pemohon->staff_id,
                'password' => Hash::make($pemohon->staff_id), // password awal = staff_id
            ]);
        } else {
            $dirty = false;

            if ($user->staff_id !== $pemohon->staff_id) {
                $user->staff_id = $pemohon->staff_id;
                $dirty = true;
            }

            if (!blank($pemohon->nama) && $user->name !== $pemohon->nama) {
                $user->name = $pemohon->nama;
                $dirty = true;
            }

            if ($dirty) {
                $user->save();
            }
        }

        // 3) Verify password + login user yang sama (elak attempt pilih record lain)
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'password' => 'Kata laluan tidak tepat.',
            ])->withInput();
        }

        // 4) Login user yang telah disync
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('app.dashboard');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('app.login');
    }
}
