@extends('layouts.backend.app')

@section('title', 'Post')

@push('css')

@endpush

@section('content')
    <div class="container-fluid">
        <!-- Vertical Layout | With Floating Label -->
        <a href="{{ route('admin.post.index') }}" class="btn btn-danger">
            <i class="material-icons">keyboard_backspace</i>
            <span>Back</span>
        </a>
        @if($post->is_approve == 1)
            <button class="btn btn-success pull-right" disabled>
                <i class="material-icons">done</i>
                <span>Approved</span>
            </button>
        @else

            <button class="btn btn-info pull-right" onclick="postApproval()">
                <i class="material-icons">done</i>
                <span>Approve</span>
            </button>
            <form action="{{ route('admin.post.approve', $post->id) }}" id="approval-form" method="POST" style="display: none">
                @csrf
                @method('PUT')
            </form>
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
                                <img style="width: 100%; height: auto" src="{{ Storage::disk('public')->url('post/'.$post->image) }}" alt="Featured Image">
                        </div>
                    </div>
                </div>
            </div>
        <!-- Vertical Layout | With Floating Label -->
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

    <script type="text/javascript">
        function postApproval(){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You want to approve this post!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('approval-form').submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'The post is still pending',
                        'info'
                    )
                }
            })
        }
    </script>
@endpush
