<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\User;
use App\Models\UserVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function verify(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::query()->where('email', $email)->first();
        # Jika Email Input Tidak Ditemukan
        if($user == null){
            return redirect()->route('login')->with('gagal', 'Email Salah');
        }
        # Jika Email Ada, Tapi Password Salah
        if(password_verify($password, $user->password) === false) {
            return redirect()->route('login')->with('gagal', 'Password Salah');
        }
        # User Tidak Aktif (is_active = 0)
        if($user->is_active == 1) {
            return redirect()->route('login')->with('gagal', 'Silahkan aktivasi akun anda lewat email');;
        }
        # Kondisi Benar
        # Buat Token`
        $token =Uuid::uuid4()->toString();
        $user->update([
            'token' => $token,
        ]);
        session([
            'token' => $token,
        ]);
        return redirect()->route('books.index');
    }

    public function logout(){
        session()->forget('token');
        return redirect()->route('login');
    }

    public function prosesRegister(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        #1. Cek apakh eemailnya exist
        $user = User::query()->where('email', $email)->first();
        if ($user !=  null) {
            return redirect()->route('login')->with('gagal', 'Email Sudah Terdaftar');
        }
        #2. Simpan data user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
        #3. Buat kode otp, menggunakan faker, 4 angka random
        $otp = rand(1000, 9999);

        #4 Menggunakan Carbon kita membuat hari ini + 1
        $expired = Carbon::now()->addDay(1);

        #5. Simpan data ke UserVerivication
        $userVerification = UserVerification::create([
            'id_user' => $user->id,
            'otp' => $otp,
            'expired' => $expired
        ]);

        #6 Kirim email ke emailnya
        Mail::to($email)->send(new RegisterMail($user, $userVerification));
        return redirect()->route('login');
    }

    public function registerVerify(Request $request)
    {
        #ambil parameter email dan otp
        $email = $request->email;
        $otp = $request->otp;
        #1. Cek apakah email nya ada
        $user = User::query()->where('email', $email)->first();
        if ($user == null) {
            return redirect()->route('login')->with('gagal', 'Email Tidak Terdaftar');
        }
        #2. Cek apakah otp nya sama
        $userVerification = UserVerification::query()->where('id_user', $user->id)->first();
        if ($userVerification == null) {
            return redirect()->route('login')->with('gagal', 'OTP Tidak Ditemukan');
        }
        if ($userVerification->otp != $otp){
            return redirect()->route('login')->with('gagal', 'OTP Tidak Ditemukan');
        }
        if ($userVerification->expired < Carbon::now()){
            return redirect()->route('login')->with('gagal', 'OTP Tidak Ditemukan');
        }
        #3. aktivasi user dengan mengganti is_active = 1
        $user->is_active = 1;
        $user->save();
        return redirect()->route('login');
    }
}

#Mail::to('xodabi7530@in2reach.com')->send(new TestMail());
