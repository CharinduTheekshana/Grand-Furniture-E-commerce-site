@extends('layouts.app')
@section('title', 'Blog - Grand Furniture')
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

            {{-- ═══ BLOG LIST ═══ --}}
            <div class="col-md-9">
                @forelse($blogs as $blog)
                <div class="single-post-list" style="margin-bottom:40px;border-bottom:1px solid #f0f0f0;padding-bottom:40px;">
                    <div class="post-list-img">
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <img src="{{ $blog->image_url }}"
                                 alt="{{ $blog->title }}"
                                 style="width:100%;height:280px;object-fit:cover;border-radius:4px;"
                                 onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'"/>
                        </a>
                    </div>
                    <div class="post-list-info" style="padding-top:20px;">
                        {{-- Meta --}}
                        <div style="display:flex;align-items:center;gap:16px;margin-bottom:12px;
                                    font-size:12px;color:#999;">
                            <span><i class="fa fa-calendar" style="margin-right:4px;"></i>
                                {{ $blog->created_at->format('d M Y') }}
                            </span>
                            <span><i class="fa fa-clock-o" style="margin-right:4px;"></i>
                                {{ $blog->reading_time }} min read
                            </span>
                        </div>

                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <h3 style="font-size:20px;margin-bottom:10px;line-height:1.4;">
                                {{ $blog->title }}
                            </h3>
                        </a>
                        <p style="color:#666;line-height:1.7;margin-bottom:16px;">
                            {{ $blog->excerpt_text }}
                        </p>
                        <a href="{{ route('blog.show', $blog->slug) }}"
                           style="display:inline-flex;align-items:center;gap:6px;
                                  color:#c8a96e;font-size:13px;font-weight:600;
                                  text-decoration:none;">
                            Continue Reading <i class="fa fa-angle-double-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fa fa-pencil-square-o" style="font-size:48px;color:#ddd;"></i>
                    <h4 style="margin-top:16px;">No blog posts yet.</h4>
                </div>
                @endforelse

                <div class="mt-4">{{ $blogs->links() }}</div>
            </div>

            {{-- ═══ SIDEBAR ═══ --}}
            <div class="col-md-3">
                <div class="sidebar-area">

                    {{-- Search --}}
                    <div class="sideber-form">
                        <form action="{{ route('blog.index') }}" method="GET">
                            <input type="text" name="q" placeholder="Search posts..."
                                   value="{{ request('q') }}"/>
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>

                    {{-- Recent Posts --}}
                    <div class="single-sidebar mt-40">
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

                    {{-- Archive --}}
                    <div class="single-sidebar mt-40">
                        <div class="sidebar-title"><h4>Archive</h4></div>
                        <div class="sidebar-list">
                            <ul>
                                @foreach($archives as $archive)
                                <li>
                                    <a href="{{ route('blog.index', ['month'=>$archive->month, 'year'=>$archive->year]) }}">
                                        {{ \Carbon\Carbon::create($archive->year, $archive->month)->format('F Y') }}
                                        <span>({{ $archive->count }})</span>
                                    </a>
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