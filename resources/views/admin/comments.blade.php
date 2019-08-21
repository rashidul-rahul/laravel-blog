@extends('layouts.backend.app')

@section('title', 'Post')

@push('css')
    <link href="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
{{--        <div class="block-header">--}}
{{--            <h2>--}}
{{--                <a href="{{ route('admin.post.create') }}" class="btn btn-primary waves-effect">--}}
{{--                    <i class="material-icons">add</i>--}}
{{--                    <span>Comments</span>--}}
{{--                </a>--}}
{{--            </h2>--}}
{{--        </div>--}}
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            All Comments
                            <span class="badge bg-green">{{ $comments->count() }}</span>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Comment Info</th>
                                    <th>Post Info</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>SL</th>
                                    <th>Comment Info</th>
                                    <th>Post Info</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($comments as $key=>$comment)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="">
                                                        <img class="media-object" src="{{ Storage::disk('public')->url('profile_pic/'.$comment->user->image)
                                                         }}" width="64" height="64" alt="User Image">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">
                                                        {{ $comment->user->name }}
                                                        <small>{{ $comment->created_at->diffForHumans() }}</small>
                                                    </h4>
                                                    <p>{{ $comment->comment }}</p>
                                                    <a href="{{ route('post.details', $comment->post->slug.'#comments') }}">Reply</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-right">
                                                    <a target="_blank" href="{{ route('post.details', $comment->post->slug) }}">
                                                        <img class="media-object" src="{{ Storage::disk('public')->url('post/'.$comment->post->image) }}"
                                                          width="64" height="64"   alt="Post Image">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">
                                                        <a target="_blank" href="{{ route('post.details', $comment->post->slug) }}">
                                                            {{ Str::limit($comment->post->title, 30, '(...)') }}
                                                        </a>
                                                    </h4>
                                                        <p>by {{ $comment->user->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-danger" onclick="deleteComment({{ $comment->id }});">
                                                <i class="material-icons">delete</i>
                                            </a>
                                            <form action="{{ route('admin.comment.destroy', $comment->id) }}" id="delete-comment-{{ $comment->id }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Exportable Table -->
    </div>

@endsection

@push('js')
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/tables/jquery-datatable.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

    <script type="text/javascript">
        function deleteComment(id){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-comment-'+id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
            })
        }

        function deleteFavorite($id){
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
                    document.getElementById('delete-favorite-'+$id).submit();
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
