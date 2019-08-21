@extends('layouts.frontend.app')

@section('title','Blog')

@push('css')
    <link href="{{ asset('assets/frontend/css/home/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/home/responsive.css') }}" rel="stylesheet">
    <style>
        .favorite_post{
            color:blue;
        }
    </style>
@endpush

@section('content')
    <div class="main-slider">
        <div class="swiper-container position-static" data-slide-effect="slide" data-autoheight="false"
             data-swiper-speed="500" data-swiper-autoplay="10000" data-swiper-margin="0" data-swiper-slides-per-view="4"
             data-swiper-breakpoints="true" data-swiper-loop="true" >
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                    <div class="swiper-slide">
                    <a class="slider-category" href="{{ route('category.post', $category->slug) }}">
                        <div class="blog-image"><img src="{{ Storage::disk('public')->url('category/slider/'.$category->image) }}" alt="Blog Image"></div>

                        <div class="category">
                            <div class="display-table center-text">
                                <div class="display-table-cell">
                                    <h3><b>{{ $category->name }}</b></h3>
                                </div>
                            </div>
                        </div>

                    </a>
                </div><!-- swiper-slide -->
                @endforeach

            </div><!-- swiper-wrapper -->

        </div><!-- swiper-container -->

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
                                    <li><a href="{{ route('post.details', $post->slug) }}"><i class="ion-chatbubble"></i>{{ $post->comments->count() }}</a></li>
                                    <li><a href="#"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
                                </ul>

                            </div><!-- blog-info -->
                        </div><!-- single-post -->
                    </div><!-- card -->
                </div><!-- col-lg-4 col-md-6 -->
                @endforeach
            </div><!-- row -->

            <a class="load-more-btn" href="{{ route('post.index') }}"><b>LOAD MORE</b></a>

        </div><!-- container -->
    </section><!-- section -->

@endsection

@push('js')
<script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
@endpush
