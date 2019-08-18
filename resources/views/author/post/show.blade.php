@extends('layouts.backend.app')

@section('title', 'Post')

@push('css')

@endpush

@section('content')
    <div class="container-fluid">
        <!-- Vertical Layout | With Floating Label -->
        <a href="{{ route('author.post.index') }}" class="btn btn-danger">
            <i class="material-icons">keyboard_backspace</i>
            <span>Back</span>
        </a>
        @if($post->is_approve == 1)
            <button class="btn btn-success pull-right" disabled>
                <i class="material-icons">done</i>
                <span>Approved</span>
            </button>
        @else
            <button class="btn btn-danger pull-right" disabled>
                <i class="material-icons">watch_later</i>
                <span>Pending</span>
            </button>
        @endif
        <br>
        <br>
        <div class="row clearfix">
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            {{ $post->title }}
                            <small>Posted by <strong>{{ $post->user->name }}</strong> on {{ $post->created_at->toFormattedDateString() }}</small>
                        </h2>
                    </div>
                    <div class="body">
                        {!! $post->body !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-cyan">
                        <h2>
                            Categories
                        </h2>
                    </div>
                    <div class="body">
                        @foreach($post->categories as $category)
                            <span class="label bg-cyan">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="card">
                    <div class="header bg-green">
                        <h2>
                            Tags
                        </h2>
                    </div>
                    <div class="body">
                        @foreach($post->tags as $tag)
                            <span class="label bg-green">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="card">
                    <div class="header bg-brown">
                        <h2>
                            Featured Image
                        </h2>
                    </div>
                    <div class="body">
                        <div class="">
                            <img style="width: 100%; height: auto;" src="{{ Storage::disk('public')->url('post/'.$post->image) }}" alt="Featured Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Vertical Layout | With Floating Label -->
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/backend/plugins/tinymce/tinymce.js') }}"></script>
    <script>
        $(function () {

            //TinyMCE
            tinymce.init({
                selector: "textarea#tinymce",
                theme: "modern",
                height: 300,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons',
                image_advtab: true
            });
            tinymce.suffix = ".min";
            tinyMCE.baseURL = '{{ asset('assets/backend/plugins/tinymce') }}';
        });
    </script>
@endpush
