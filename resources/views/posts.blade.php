@extends('layouts.frontend.app')

@section('title', 'title')

@push('css')
    <link href="{{ asset('assets/frontend/css/posts/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/posts/responsive.css') }}" rel="stylesheet">
    <style>

        .favorite_post{
            color:blue;
        }
    </style>
@endpush

@section('content')
    <div class="slider display-table center-text">
        <h1 class="title display-table-cell"><b>All Post</b></h1>
    </div><!-- slider -->

    <section class="blog-area section">
        <div class="container">

            <div class="row">
                @foreach($posts as $post)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="single-post post-style-1">

                                <div class="blog-image"><img src="{{ Storage::disk('public')->url('post/'.$post->image) }}" alt="Blog Image"></div>

                                <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile_pic/'.$post->user->image) }}" alt="Profile Image"></a>

                                <div class="blog-info">

                                    <h4 class="title"><a href="{{ route('post.details', $post->slug) }}"><b>{{ $post->title }}</b></a></h4>

                                    <ul class="post-footer">
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
                                        <li><a href="#"><i class="ion-chatbubble"></i>6</a></li>
                                        <li><a href="#"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
                                    </ul>

                                </div><!-- blog-info -->
                            </div><!-- single-post -->
                        </div><!-- card -->
                    </div><!-- col-lg-4 col-md-6 -->
                @endforeach


            </div>
            {{ $posts->links() }}
        </div><!-- container -->
    </section><!-- section -->


@endsection

@push('js')

@endpush
