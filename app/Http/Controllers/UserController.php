<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
           'except' => ['index', 'show' ,'create', 'store']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
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
//        $this->authorize('show', $user);
        return view('users.show', compact('user'));
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

        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    public function destroy()
    {

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
}
