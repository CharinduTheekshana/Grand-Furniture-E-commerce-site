@extends('layouts.app')
@section('title', $blog->title . ' - Grand Furniture')
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

            {{-- Blog Content --}}
            <div class="col-md-9">
                <div class="single-post-list">
                    <div class="post-list-img">
                        <img src="{{ $blog->image ? asset('storage/'.$blog->image) : asset('assets/images/blog/1.jpg') }}"
                             alt="{{ $blog->title }}" style="width:100%;"
                             onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'"/>
                    </div>
                    <div class="post-list-info" style="padding-top:20px;">
                        <h3>{{ $blog->title }}</h3>
                        <h4>{{ $blog->created_at->format('d M Y') }}</h4>
                        <div style="margin-top:20px;line-height:1.8;">{!! $blog->content !!}</div>
                        <div style="margin-top:30px;padding-top:20px;border-top:1px solid #eee;">
                            <a href="{{ route('blog.index') }}" style="color:#333;font-size:13px;">
                                <i class="fa fa-arrow-left"></i> Back to Blog
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Related Posts --}}
                
                @if($relatedBlogs->count() > 0)
                <div style="margin-top:50px;border-top:2px solid #eee;padding-top:30px;">
                    <h4 style="text-transform:uppercase;margin-bottom:25px;">Related Posts</h4>
                    <div class="row">
                        @foreach($relatedBlogs as $r)
                        <div class="col-md-4">
                            <div class="single-blog" style="margin-bottom:20px;">
                                <div class="blog-img">
                                    <a href="{{ route('blog.show',$r->slug) }}">
                                        <img src="{{ $r->image ? asset('storage/'.$r->image) : asset('assets/images/blog/1.jpg') }}"
                                             alt="{{ $r->title }}" onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'"/>
                                    </a>
                                </div>
                                <div class="blog-info">
                                    <a href="{{ route('blog.show',$r->slug) }}"><h2>{{ Str::limit($r->title,30) }}</h2></a>
                                    <p>{{ Str::limit(strip_tags($r->content),80) }}</p>
                                    <a href="{{ route('blog.show',$r->slug) }}">Read more <span class="lnr lnr-arrow-right"></span></a>
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
                    <div class="sideber-form">
                        <form action="{{ route('blog.index') }}" method="GET">
                            <input type="text" name="q" placeholder="Search Post Here..."/>
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div class="single-sidebar">
                        <div class="sidebar-title"><h4>Recent Posts</h4></div>
                        <div class="sidebar-list">
                            <ul>
                                @foreach($recentBlogs as $recent)
                                <li><a href="{{ route('blog.show',$recent->slug) }}" {{ $recent->id==$blog->id?'style=font-weight:bold':'' }}>
                                    {{ Str::limit($recent->title,35) }}
                                </a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="single-sidebar">
                        <div class="sidebar-title"><h4>Archive</h4></div>
                        <div class="sidebar-list">
                            <ul>
                                
                                @foreach($archives as $archive)
                                <li><a href="{{ route('blog.index',['month'=>$archive->month,'year'=>$archive->year]) }}">
                                    {{ \Carbon\Carbon::createFromDate($archive->year,$archive->month,1)->format('F Y') }} ({{ $archive->count }})
                                </a></li>
                                @endforeach
                            </ul>
                        </div>
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