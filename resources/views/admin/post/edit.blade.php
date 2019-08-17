@extends('layouts.backend.app')

@section('title', 'Post')

@push('css')
    <link href="{{ asset('assets/backend/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit Post</h2>
        </div>
        <!-- Vertical Layout | With Floating Label -->
        <form action="{{ route('admin.post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Vertical Layout | With Floating Label -->
            <div class="row clearfix">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Title and image
                            </h2>
                        </div>
                        <div class="body">

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" id="title" name="title" class="form-control" value="{{ $post->title }}">
                                    <label for="title" class="form-label">Title</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label for="image">Featured Image</label>
                                    <input type="file" name="image" >
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="publish" name="status" class="filled-in" value="1" {{ $post->status == true ? 'checked':'' }}>
                                <label for="publish">Publish</label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Category and Tags
                            </h2>
                        </div>
                        <div class="body">

                            <div class="form-group form-float">
                                <div class="form-line {{ $errors->has('categories') ? 'focused error': '' }}">
                                    <label>Select Category</label>
                                    <select name="categories[]" id="category" class="form-control show-tick" data-live-search="true" multiple>
                                        @foreach($categories as $category)
                                            <option
                                                @foreach($post->categories as $postCat)
                                                    {{ $postCat->id == $category->id ? 'selected':'' }}
                                                @endforeach
                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line {{ $errors->has('tags') ? 'focused error': '' }}">
                                    <label>Select Tags</label>
                                    <select name="tags[]" id="tag" class="form-control show-tick" data-live-search="true" multiple>
                                        @foreach($tags as $tag)
                                            <option
                                                @foreach($post->tags as $postTag)
                                                {{ $postTag->id == $tag->id ? 'selected':'' }}
                                                @endforeach
                                                value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.post.index') }}">BACK</a>
                                    <button class="btn btn-success" type="submit">CREATE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Body
                            </h2>
                        </div>
                        <div class="body">
                            <textarea name="body" id="tinymce">{{ $post->body }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
