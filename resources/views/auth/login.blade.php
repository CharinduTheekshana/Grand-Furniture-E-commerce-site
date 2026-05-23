@extends('layouts.app')
@section('title','Customer Login - Grand Furniture')
@section('content')
<div class="page-title-wrapper"><div class="container"><div class="row"><div class="col-lg-12"><div class="page-title"><h3>Customer Login</h3></div></div></div></div></div>
<div class="login-area ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="login-title"><h3>Registered Customers</h3><span>If you have an account, sign in with your email address.</span></div>
                <div class="login-form">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group login-page">
                            <label>Email <span>*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')<span class="text-danger" style="font-size:13px">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group login-page">
                            <label>Password <span>*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')<span class="text-danger" style="font-size:13px">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group"><div class="checkbox"><label><input type="checkbox" name="remember"> Remember Me</label></div></div>
                        <button type="submit" class="btn btn-default login-btn">Sign In</button>
                    </form>
                </div>
                @if(Route::has('password.request'))<a href="{{ route('password.request') }}" class="back">Forgot Your Password?</a>@endif
            </div>
            <div class="col-md-6">
                <div class="login-title"><h3>New Customers</h3><span>Creating an account has many benefits: check out faster, keep more than one address, track orders and more.</span></div>
                <a href="{{ route('register') }}"><button type="button" class="btn btn-default login-btn">Create an Account</button></a>
            </div>
        </div>
    </div>
</div>
<div class="contact-area ptb-40"><div class="container"><div class="row"><div class="col-lg-4 mar_b-30"><div class="contuct-info text-center"><h4>Sign up for news &amp; offers!</h4><p>You may safely unsubscribe at any time</p></div></div><div class="col-xl-6 col-lg-7 offset-lg-1"><div class="search-box"><form action="#"><input type="email" placeholder="Enter your email address" /><button><span class="lnr lnr-envelope"></span></button></form></div></div></div></div></div>
@endsection
