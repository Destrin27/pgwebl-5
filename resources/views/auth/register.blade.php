@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Register</h3>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <input type="text" name="name" placeholder="Nama" class="form-control mb-2">
        <input type="email" name="email" placeholder="Email" class="form-control mb-2">
        <input type="password" name="password" placeholder="Password" class="form-control mb-2">
        <input type="password" name="password_confirmation" placeholder="Konfirmasi" class="form-control mb-2">

        <button class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
