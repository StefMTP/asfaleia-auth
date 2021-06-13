@extends('layouts.main')

@section('content')
    @auth
        <h1>You are logged in. You can now see the contents of this page.</h1>
        <p>Hello World! Your account info:</p>
        <ul>
            <li>Username: {{Auth::user()->username}}</li>
            <li>Description: {{Auth::user()->description}}</li>
        </ul>
        <form action="{{route('logout')}}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endauth
@endsection