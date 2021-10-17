<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:2,1', [
            'only' => ['resetForm']
        ]);
    }

    public function resetForm()
    {
        return view('password.resetForm');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            session()->flash('danger', '邮箱未注册');
            return redirect()->back()->withInput();
        }

        $token = hash_hmac('sha256', Str::random(40), config('app.key'));
        DB::table('password_resets')->updateOrInsert(['email' => $email],[
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => new Carbon,
        ]);

        Mail::send('email.reset_link', compact('token'), function ($message) use ($email) {
            $message->to($email)->subject("忘记密码");
        });

        session()->flash('success', '重置邮件发送成功，请查收');
        return redirect()->back();
    }

    public function showResetForm($token)
    {
        return view('password.reset', compact('token'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'email|required',
            'password' => 'required|confirmed|min:8'
        ]);
        $token = $request->post('token');
        $email = $request->post('email');

        $user = User::where('email', $email)->first();

        // if it does exist
        if (is_null($user)) {
            session()->flash('failed', '邮箱未注册');
            return redirect()->back()->withInput();
        }

        // 设置邮件的有效期 10 minutes
        $expires = 60 * 10;

        $record = DB::table('password_resets')->where('email', $email)->first();
        if ($record) {
            if (Carbon::parse($record->created_at)->addSecond($expires)->isPast()) {
                session()->flash('danger', '链接已过期');
                return redirect()->back();
            }

            if (!Hash::check($token, $record->token)) {
                session()->flash('danger', 'token failed');
                return redirect()->back();
            }
            $user->update(['password' => bcrypt($request->password)]);
            session()->flash('success', '密码重置成功，请使用新密码登录');
            return redirect()->route('login');
        }
    }

}
