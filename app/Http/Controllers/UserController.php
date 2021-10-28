<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
           'except' => ['index', 'show' ,'create', 'store', 'confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);

        $this->middleware('throttle:10,60', [
            'only' => ['store']
        ]);
    }

    public function index()
    {
        $users = User::paginate(6);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        $statuses = $user->status()
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);
        return view('users.show', compact('user', 'statuses'));
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $this->sendEmailConfirmation($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }

    public function sendEmailConfirmation($user)
    {
        $view = 'email.confirm';
        $data = compact('user');
        $from = 'summer@example.com';
        $name = 'summer';
        $to = $user->email;
        $subject = '欢迎注册weibo应用！ 请确认激活邮箱';

        Mail::send($view, $data, function($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);

        $user->delete();
        session()->flash('success', 'delete success');
        return back();
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validateData = $request->validate([
            'name' => 'required|unique:users|max:50',
            'password' => 'nullable|confirmed|min:6',
        ]);

        if ($validateData['password'] && $validateData['name']) {
            $user->update([
                'name' => $request->input('name'),
                'password' => bcrypt($request->input('password'))
            ]);
        } else if ($validateData['name'] && empty($validateData['password'])) {
            $user->update([
                'name' => $request->input('name'),
            ]);
        }
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);

    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token' , $token)->firstOrFail();
        $user->activated = 1;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜邮件激活成功');
        return redirect()->route('users.show', [$user]);
    }

    public function following(User $user)
    {
        $followings = $user->followings()->paginate(30);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('followings', 'title'));
    }

    public function follower(User $user)
    {
        $followers = $user->followers()->paginate(30);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('followers', 'title'));
    }
}
