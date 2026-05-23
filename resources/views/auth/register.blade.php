@extends('layouts.app')
@section('title','Create Account - Grand Furniture')
@section('content')
<div class="page-title-wrapper"><div class="container"><div class="row"><div class="col-lg-12"><div class="page-title"><h3>Create New Customer Account</h3></div></div></div></div></div>
<div class="login-area ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-8">
                <div class="login-form">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="login-title"><h3>Personal Information</h3></div>
                        <div class="form-group login-page">
                            <label>Full Name <span>*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')<span class="text-danger" style="font-size:13px">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group"><div class="checkbox"><label><input type="checkbox" name="newsletter"> Sign Up for Newsletter</label></div></div>
                        <div class="login-title"><h3>Sign-in Information</h3></div>
                        <div class="form-group login-page">
                            <label>Email <span>*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')<span class="text-danger" style="font-size:13px">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group login-page">
                            <label>Password <span>*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')<span class="text-danger" style="font-size:13px">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group login-page">
                            <label>Confirm Password <span>*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-default login-btn">Create an Account</button>
                    </form>
                </div>
                <a href="{{ route('login') }}" class="back">Back to Login</a>
            </div>
        </div>
    </div>
</div>
<div class="contact-area ptb-40"><div class="container"><div class="row"><div class="col-lg-4 mar_b-30"><div class="contuct-info text-center"><h4>Sign up for news &amp; offers!</h4><p>You may safely unsubscribe at any time</p></div></div><div class="col-xl-6 col-lg-7 offset-lg-1"><div class="search-box"><form action="#"><input type="email" placeholder="Enter your email address" /><button><span class="lnr lnr-envelope"></span></button></form></div></div></div></div></div>
@endsection
