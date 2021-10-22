<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
//        dd(Auth::id());
        $request->validate([
           'content' => 'required|max:150',
        ]);
        Auth::user()->status()->create([
            'content' => $request['content']
        ]);
        session()->flash('success', '发布成功');
        return redirect()->back();
    }
}
