@extends('layouts.app')
@section('title', 'Contact Us - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>Contact Us</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="google-map-area ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="google-map" id="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.838709675939!2d144.95320007668528!3d-37.817246734238516!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d4dd5a05d97%3A0x3e64f855a564844d!2s121%20King%20St%2C%20Melbourne%20VIC%203000%2C%20Australia!5e0!3m2!1sen!2sus!4v1670477011653!5m2!1sen!2sus"
                        style="border:0;width:100%;height:400px;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contuct-form-area pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contuct-form_area">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="form-group contuct_f">
                            <label for="name">Name <span>*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group contuct_f">
                            <label for="email">Email <span>*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group contuct_f">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control"
                                   id="phone" name="phone" placeholder="Phone Number" value="{{ old('phone') }}">
                        </div>
                        <div class="form-group contuct_f">
                            <label for="message">What is on your mind? <span>*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      id="message" name="message" rows="5">{{ old('message') }}</textarea>
                            @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-default contact-btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Newsletter --}}
<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30">
                <div class="contuct-info text-center">
                    <h4>Sign up for news & offers!</h4>
                    <p>You may safely unsubscribe at any time</p>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7 offset-lg-1">
                <div class="search-box">
                    <form action="#">
                        <input type="email" placeholder="Enter your email address" />
                        <button><span class="lnr lnr-envelope"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
