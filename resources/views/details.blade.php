@extends('layouts.frontend.app')

@section('title', 'Post details')

@push('css')
    <link href="{{ asset('assets/frontend/css/details/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/details/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/css/ionicons.css') }}" rel="stylesheet">

    <style>
        .details-slider{
            height: 400px;
            width: 100%;
            background-size: cover;
            margin: 0;
            background-image: url({{ Storage::disk('public')->url('post/'.$post->image) }});
        }

        .favorite_post{
             color:blue;
         }
    </style>
@endpush

@section('content')
    <div class="details-slider">
        <div class="display-table  center-text">
            <h1 class="title display-table-cell"><b></b></h1>
        </div>
    </div>
    <section class="post-area section">
        <div class="container">

            <div class="row">

                <div class="col-lg-8 col-md-12 no-right-padding">

                    <div class="main-post">

                        <div class="blog-post-inner">

                            <div class="post-info">

                                <div class="left-area">
                                    <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile_pic/'.$post->user->image) }}" alt="Profile Image"></a>
                                </div>

                                <div class="middle-area">
                                    <a class="name" href="#"><b>{{ $post->user->name }}</b></a>
                                    <h6 class="date">on {{ $post->created_at->diffForHumans() }}</h6>
                                </div>

                            </div><!-- post-info -->

                            <h3 class="title"><a href="#"><b>{{ $post->title }}</b></a></h3>

                            <p class="para">
                                {!! $post->body !!}
                            </p>

                            <ul class="tags">
                                @foreach($post->tags as $tag)
                                <li><a href="#">{{ $tag->name }}</a></li>
                                @endforeach
                            </ul>
                        </div><!-- blog-post-inner -->

                        <div class="post-icons-area">
                            <ul class="post-icons">
                                <li>
                                    @guest
                                        <a href="javascript:void(0)" onclick="toastr.info('You have to login to add post to favorite', 'Info', {
                                                closeButton:true,
                                                progressBar:true,
                                            })"><i class="ion-heart"></i>{{ $post->favorite_post_user()->count() }}</a>
                                    @else
                                        <a href="javascript:void(0);" onclick="document.getElementById('favorite-form-{{ $post->id }}').submit();"
                                           class="{{ Auth::user()->favorite_post()->where('post_id', $post->id)->count() == 0 ? '':'favorite_post' }}">

                                            <i class="ion-heart"></i>

                                            {{ $post->favorite_post_user()->count() }}

                                        </a>
                                        <form action="{{ route('post.favorite', $post->id) }}" id="favorite-form-{{ $post->id }}" method="POST">@csrf</form>
                                    @endguest

                                </li>
                                <li><a href="javascript:void(0);"><i class="ion-chatbubble"></i>{{ $post->comments->count() }}</a></li>
                                <li><a href="javascript:void(0);"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
                            </ul>

                            <ul class="icons">
                                <li>SHARE : </li>
                                <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                <li><a href="#"><i class="ion-social-pinterest"></i></a></li>
                            </ul>
                        </div>
                        <!-- post-info -->


                    </div><!-- main-post -->
                </div><!-- col-lg-8 col-md-12 -->

                <div class="col-lg-4 col-md-12 no-left-padding">

                    <div class="single-post info-area">

                        <div class="sidebar-area about-area">
                            <h4 class="title"><b>ABOUT {{ strtoupper($post->user->name) }}</b></h4>
                            <p>{{ $post->user->about }}</p>
                        </div>

                        <div class="sidebar-area subscribe-area">

                            <h4 class="title"><b>SUBSCRIBE</b></h4>
                            <div class="input-area">
                                <form method="POST" action="{{ route('subscribe.store') }}">
                                    @csrf
                                    <input class="email-input" name="email" type="email" placeholder="Enter your email">
                                    <button class="submit-btn" type="submit"><i class="icon ion-ios-email-outline"></i></button>
                                </form>
                            </div>

                        </div><!-- subscribe-area -->

                        <div class="tag-area">

                            <h4 class="title"><b>CATEGORY CLOUD</b></h4>
                            <ul>
                                @foreach($categories as $category)
                                <li><a href="#">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>

                        </div><!-- subscribe-area -->

                    </div><!-- info-area -->

                </div><!-- col-lg-4 col-md-12 -->

            </div><!-- row -->

        </div><!-- container -->
    </section><!-- post-area -->


    <section class="recomended-area section">
        <div class="container">
            <div class="row">

                @foreach($randomPost as $randPost)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="single-post post-style-1">

                            <div class="blog-image"><img src="{{ Storage::disk('public')->url('post/'.$randPost->image) }}" alt="Blog Image"></div>

                            <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile_pic/'.$randPost->user->image) }}" alt="Profile Image"></a>

                            <div class="blog-info">

                                <h4 class="title"><a href="{{ route('post.details', $randPost->slug) }}"><b>{{ $randPost->title }}</b></a></h4>

                                <ul class="post-footer">
                                    <li>
                                        @guest
                                            <a href="javascript:void(0)" onclick="toastr.info('You have to login to add post to favorite', 'Info', {
                                                closeButton:true,
                                                progressBar:true,
                                            })"><i class="ion-heart"></i>{{ $randPost->favorite_post_user()->count() }}</a>
                                        @else
                                            <a href="javascript:void(0);" onclick="document.getElementById('favorite-form-{{ $randPost->id }}').submit();"
                                               class="{{ Auth::user()->favorite_post()->where('post_id', $randPost->id)->count() == 0 ? '':'favorite_post' }}">

                                                <i class="ion-heart"></i>

                                                {{ $randPost->favorite_post_user()->count() }}

                                            </a>
                                            <form action="{{ route('post.favorite', $randPost->id) }}" id="favorite-form-{{ $randPost->id }}" method="POST">@csrf</form>
                                        @endguest

                                    </li>
                                    <li><a href="{{ route('post.details', $randPost->slug) }}"><i class="ion-chatbubble"></i>{{ $randPost->comments->count() }}</a></li>
                                    <li><a href="javascript:void(0);"><i class="ion-eye"></i>{{ $randPost->view_count }}</a></li>
                                </ul>

                            </div><!-- blog-info -->
                        </div><!-- single-post -->
                    </div><!-- card -->
                </div><!-- col-md-6 col-sm-12 -->
                @endforeach

            </div><!-- row -->

        </div><!-- container -->
    </section>

    <section class="comment-section">
        <div class="container">
            <h4><b>POST COMMENT</b></h4>
            <div class="row">

                <div class="col-lg-8 col-md-12">
                    @guest
                          <p>For post comment you must <a href="{{ route('login') }}">Login</a></p><br><br>
                    @else
                        <div class="comment-form">
                            <form method="POST" action="{{ route('comment.store') }}">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <div class="row">
                                    <div class="col-sm-12">
									<textarea name="comment" rows="2" class="text-area-messge form-control"
                                              placeholder="Enter your comment" aria-required="true" aria-invalid="false"></textarea >
                                    </div><!-- col-sm-12 -->
                                    <div class="col-sm-12">
                                        <button class="submit-btn" type="submit" id="form-submit"><b>POST COMMENT</b></button>
                                    </div><!-- col-sm-12 -->

                                </div><!-- row -->
                            </form>
                        </div>
                    @endguest

                    <h4><b>COMMENTS <span class="badge badge-warning">{{ $post->comments->count() }}</span></b></h4>
                        @foreach($post->comments as $comment)
                    <div class="commnets-area">

                        <div class="comment">

                            <div class="post-info">

                                <div class="left-area">
                                    <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile_pic/'.$comment->user->image) }}" alt="Profile Image"></a>
                                </div>

                                <div class="middle-area">
                                    <a class="name" href="#"><b>{{ $comment->user->name }}</b></a>
                                    <h6 class="date">on {{ $comment->created_at->diffForhumans() }}</h6>
                                </div>


                            </div><!-- post-info -->

                            <p>{{ $comment->comment }}</p>

                        </div>


                    </div><!-- commnets-area -->
                        @endforeach

{{--                    <a class="more-comment-btn" href="#"><b>VIEW MORE COMMENTS</a>--}}

                </div><!-- col-lg-8 col-md-12 -->

            </div><!-- row -->

        </div><!-- container -->
    </section>

@endsection

@push('js')

@endpush
