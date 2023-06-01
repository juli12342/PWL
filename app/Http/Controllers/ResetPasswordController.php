<?php

namespace App\Http\Controllers;

use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Svg\Tag\Rect;

class ResetPasswordController extends Controller
{
    public function index()
    {
        return view('fp.index');
    }

    public function reset(Request $request)
    {
        $email = $request->email;
        $user = User::query()->where('email', $email)->first();
        if ($user == null) {
            return redirect()->route('login')->with('gagal', 'Email Tidak Ditemukan');
        }
        $token = Uuid::uuid4()->toString();
        $resetPassword = ResetPassword::create([
            'id_user' => $user->id,
            'token' => $token,
            'expired' => Carbon::now()->addDays(1),
            'is_used' => 0
        ]);
        #kirim email
        Mail::to($email)
            ->send(new ResetPasswordMail($user, $resetPassword));
        return redirect()->route('login');
    }

    public function newPasswordForm(Request $request)
    {

        return view('fp.reset_password', [
            //tanda 2 kali if null
            'email' => $request->email ?? '',
            'token' => $request->token ?? ''
        ]);
    }

    public function newPasswordProses(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $newPassword = $request->new_password;
        $user = User::query()->where('email', $email)->first();
        if ($user == null) {
            return redirect()->back()->with('gagal', 'Email tidak ditemukan');
        }
        $resetPassword = ResetPassword::query()
            ->where('id_user', $user->id)
            ->where('token', $token)
            ->first();
        if ($resetPassword == null) {
            return redirect()->back()
            ->with('gagal', 'Token tidak ditemukan');
        }
        if ($resetPassword->expired < Carbon::now()) {
            return redirect()->back()
            ->with('gagal', 'Token sudah expired');
        }
        if ($resetPassword->is_used === 1) {
            return redirect()->back()->with('gagal', 'Token sudah digunakan');
        }
        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->save();
        $resetPassword->is_used = 1;
        $resetPassword->save();
        return redirect()->route('login');
    }
}
