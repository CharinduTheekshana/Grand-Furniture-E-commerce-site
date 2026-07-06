@extends('layouts.app')
@section('title', $blog->title . ' - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>Blog</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="post-list-wrapper-area ptb-80">
    <div class="container">
        <div class="row">

            {{-- Blog Content --}}
            <div class="col-md-9">
                <div class="single-post-list">

                    {{-- Featured Image --}}
                    <div class="post-list-img">
                        <img src="{{ $blog->image_url }}"
                             alt="{{ $blog->title }}"
                             style="width:100%;max-height:400px;object-fit:cover;border-radius:6px;"
                             onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'"/>
                    </div>

                    <div class="post-list-info" style="padding-top:24px;">

                        {{-- Meta --}}
                        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:16px;
                                    margin-bottom:16px;font-size:12px;color:#999;">
                            <span><i class="fa fa-calendar" style="margin-right:4px;color:#c8a96e;"></i>
                                {{ $blog->created_at->format('d M Y') }}
                            </span>
                            <span><i class="fa fa-clock-o" style="margin-right:4px;color:#c8a96e;"></i>
                                {{ $blog->reading_time }} min read
                            </span>
                            <span><i class="fa fa-eye" style="margin-right:4px;color:#c8a96e;"></i>
                                Grand Furniture Blog
                            </span>
                        </div>

                        {{-- Title --}}
                        <h2 style="font-size:26px;font-weight:700;margin-bottom:20px;line-height:1.4;">
                            {{ $blog->title }}
                        </h2>

                        {{-- Content --}}
                        <div style="line-height:1.9;color:#555;font-size:15px;">
                            {!! $blog->content !!}
                        </div>

                        {{-- Share --}}
                        <div style="margin-top:30px;padding:20px;background:#fafafa;
                                    border-radius:6px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                            <span style="font-size:13px;font-weight:700;color:#333;">Share this post:</span>
                            @php $shareUrl = urlencode(request()->url()); $shareTitle = urlencode($blog->title); @endphp
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                               target="_blank"
                               style="display:inline-flex;align-items:center;gap:6px;background:#1877f2;
                                      color:#fff;padding:6px 14px;border-radius:4px;font-size:12px;
                                      text-decoration:none;">
                                <i class="fa fa-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                               target="_blank"
                               style="display:inline-flex;align-items:center;gap:6px;background:#1da1f2;
                                      color:#fff;padding:6px 14px;border-radius:4px;font-size:12px;
                                      text-decoration:none;">
                                <i class="fa fa-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
                               target="_blank"
                               style="display:inline-flex;align-items:center;gap:6px;background:#25d366;
                                      color:#fff;padding:6px 14px;border-radius:4px;font-size:12px;
                                      text-decoration:none;">
                                <i class="fa fa-whatsapp"></i> WhatsApp
                            </a>
                            <button onclick="navigator.clipboard.writeText(window.location.href).then(()=>showToast('Link copied!','success'))"
                                    style="display:inline-flex;align-items:center;gap:6px;background:#666;
                                           color:#fff;padding:6px 14px;border-radius:4px;font-size:12px;
                                           border:none;cursor:pointer;">
                                <i class="fa fa-link"></i> Copy Link
                            </button>
                        </div>

                        {{-- Back + Nav --}}
                        <div style="margin-top:24px;display:flex;justify-content:space-between;
                                    align-items:center;flex-wrap:wrap;gap:10px;">
                            <a href="{{ route('blog.index') }}"
                               style="color:#333;font-size:13px;display:flex;align-items:center;gap:6px;">
                                <i class="fa fa-arrow-left"></i> Back to Blog
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Related Posts --}}
                @if($relatedBlogs->count() > 0)
                <div style="margin-top:50px;border-top:2px solid #eee;padding-top:30px;">
                    <h4 style="text-transform:uppercase;margin-bottom:25px;font-size:16px;">Related Posts</h4>
                    <div class="row">
                        @foreach($relatedBlogs as $r)
                        <div class="col-md-4 mb-4">
                            <div class="single-blog">
                                <div class="blog-img">
                                    <a href="{{ route('blog.show', $r->slug) }}">
                                        <img src="{{ $r->image_url }}"
                                             alt="{{ $r->title }}"
                                             style="width:100%;height:160px;object-fit:cover;border-radius:4px;"
                                             onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'">
                                    </a>
                                </div>
                                <div style="padding-top:12px;">
                                    <span style="font-size:11px;color:#999;">
                                        {{ $r->created_at->format('d M Y') }} · {{ $r->reading_time }} min read
                                    </span>
                                    <a href="{{ route('blog.show', $r->slug) }}">
                                        <h5 style="font-size:14px;margin-top:6px;line-height:1.4;">
                                            {{ Str::limit($r->title, 55) }}
                                        </h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-md-3">
                <div class="sidebar-area">

                    {{-- Recent Posts --}}
                    <div class="single-sidebar">
                        <div class="sidebar-title"><h4>Recent Posts</h4></div>
                        <div class="sidebar-list">
                            <ul>
                                @foreach($recentBlogs as $recent)
                                <li style="display:flex;gap:10px;padding:10px 0;border-bottom:1px solid #f5f5f5;">
                                    <img src="{{ $recent->image_url }}"
                                         style="width:60px;height:50px;object-fit:cover;border-radius:4px;flex-shrink:0;"
                                         onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'">
                                    <div>
                                        <a href="{{ route('blog.show', $recent->slug) }}"
                                           style="font-size:12px;color:#333;font-weight:600;line-height:1.4;display:block;">
                                            {{ Str::limit($recent->title, 40) }}
                                        </a>
                                        <span style="font-size:11px;color:#999;">
                                            {{ $recent->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Banner --}}
                    <div class="sideber-ads mt-40">
                        <a href="{{ route('shop') }}">
                            <img src="{{ asset('assets/images/banner/13.jpg') }}" alt="Sale"
                                 style="width:100%;border-radius:4px;">
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30"><div class="contuct-info text-center"><h4>Sign up for news &amp; offers!</h4><p>You may safely unsubscribe at any time</p></div></div>
            <div class="col-xl-6 col-lg-7 offset-lg-1"><div class="search-box"><form action="#"><input type="email" placeholder="Enter your email address"/><button><span class="lnr lnr-envelope"></span></button></form></div></div>
        </div>
    </div>
</div>

@endsection