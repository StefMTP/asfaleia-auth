@extends('layouts.main')

@section('content')
<h2>Register your account.</h2>
<form method="POST" action="{{ route('register')}}">
    @csrf
    <div>
        {{(Session::get('message'))}}
    </div>
    <div>
        <label for="username">Username</label>
        <input type="text" name="username">
        <span>@error('username') {{ $message }} @enderror</span>
    </div>
    <div>
        <label for="description">Description</label>
        <textarea name="description"></textarea>
        <span>@error('description') {{ $message }} @enderror</span>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password">
        <span>@error('password') {{ $message }} @enderror</span>
    </div>
    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation">
    </div>    
    <button type="submit">Register</button>
</form>
<a href="{{route('login')}}">Already registered? Login</a>
@endsection