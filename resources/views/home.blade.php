@extends('layouts.main')

@section('content')
<h2>Welcome, please log in.</h2>
<form method="POST" action="{{ route('login')}}">
    @csrf
    <div>
        {{(Session::get('message'))}}
    </div>
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" value="{{old('username')}}">
        <span>@error('username') {{ $message }} @enderror</span>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password">
        <span>@error('password') {{ $message }} @enderror</span>
    </div>
    <button type="submit">Login</button>
</form>
<a href="{{route('register')}}">No account? Register here</a>
@endsection