@extends('layouts.app')
@section('title', 'Blog - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>blog</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="post-list-wrapper-area ptb-80">
    <div class="container">
        <div class="row">

            {{-- ═══ BLOG LIST col-md-9 ═══ --}}
            <div class="col-md-9">
                @forelse($blogs as $blog)
                <div class="single-post-list">
                    <div class="post-list-img">
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <img src="{{ $blog->image ? asset('storage/'.$blog->image) : asset('assets/images/blog/1.jpg') }}"
                                 alt="{{ $blog->title }}"
                                 onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'"/>
                        </a>
                    </div>
                    <div class="post-list-info">
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <h3>{{ $blog->title }}</h3>
                        </a>
                        <p>{{ Str::limit(strip_tags($blog->content), 150) }}</p>
                        <h4>{{ $blog->created_at->format('d M') }}</h4>
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <span>Continus Reading <i class="fa fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-5"><h4>No blog posts yet.</h4></div>
                @endforelse

                <div class="mt-4">{{ $blogs->links() }}</div>
            </div>

            {{-- ═══ SIDEBAR col-md-3 ═══ --}}
            <div class="col-md-3">
                <div class="sidebar-area">

                    {{-- Search --}}
                    <div class="sideber-form">
                        <form action="{{ route('blog.index') }}" method="GET">
                            <input type="text" name="q" placeholder="Search Post Here..." value="{{ request('q') }}"/>
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>

                    {{-- Recent Posts --}}
                    <div class="single-sidebar">
                        <div class="sidebar-title"><h4>Recent Posts</h4></div>
                        <div class="sidebar-list">
                            <ul>
                                @foreach($recentBlogs as $recent)
                                <li><a href="{{ route('blog.show', $recent->slug) }}">{{ Str::limit($recent->title, 35) }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Archive --}}
                    <div class="single-sidebar">
                        <div class="sidebar-title"><h4>Archive</h4></div>
                        <div class="sidebar-list">
                            <ul>
                                
                                @foreach($archives as $archive)
                                <li>
                                    <a href="{{ route('blog.index', ['month'=>$archive->month, 'year'=>$archive->year]) }}">
                                        {{ \Carbon\Carbon::createFromDate($archive->year, $archive->month, 1)->format('F Y') }}
                                        ({{ $archive->count }})
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

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
                    <h4>Sign up for news &amp; offers!</h4>
                    <p>You may safely unsubscribe at any time</p>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7 offset-lg-1">
                <div class="search-box">
                    <form action="#">
                        <input type="email" placeholder="Enter your email address"/>
                        <button><span class="lnr lnr-envelope"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection