@extends('layouts.default')
@section('title', '登录')
@section('content')
    <div class="offset-md-2 col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>SignIN</h5>
            </div>
            <div class="card-body">
                @include('shared._errors')

                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" class="form-control" value="{{old('email')}}">
                    </div>

                    <div class="form-group">
                        <label for="password">password</label>
                        <input type="password" name="password" class="form-control" value="{{old('password')}}">
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="remember" id="exampleCheck">
                            <label class="form-check-label" for="exampleCheck" >Remember Me</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <hr>

                <p>还没账号？<a href="{{ route('users.create') }}">现在注册！</a></p>

            </div>
        </div>
    </div>
@stop
