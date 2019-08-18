@extends('layouts.backend.app')

@section('title', 'Post')

@push('css')
    <link href="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                <a href="{{ route('admin.post.create') }}" class="btn btn-primary waves-effect">
                    <i class="material-icons">add</i>
                    <span>Create New Post</span>
                </a>
            </h2>
        </div>
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            All Post
                            <span class="badge bg-green">{{ $posts->count() }}</span>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th><i class="material-icons">visibility</i></th>
                                    <th>Is Approve</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th><i class="material-icons">visibility</i></th>
                                    <th>Is Approve</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($posts as $key=>$post)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ substr($post->title, 0, 15) }}</td>
                                    <td>{{ $post->user->name }}</td>
                                    <td>{{ $post->view_count }}</td>
                                    <td>
                                        @if($post->is_approve)
                                            <span class="badge bg-blue">Approved</span>
                                        @else
                                            <span class="badge bg-pink">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($post->status)
                                            <span class="badge bg-blue">Published</span>
                                        @else
                                            <span class="badge bg-pink">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-info waves-effect" onclick="postApproval()">
                                            <i class="material-icons">done</i>
                                        </button>
                                        <form style="display:none;" action="{{ route('admin.post.approve', $post->id) }}" id="approval-form" method="POST">@csrf @method('PUT')</form>

                                        <a class="btn btn-primary waves-effect" href="{{ route('admin.post.edit', $post->id) }}">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <button class="btn btn-danger" onclick="deletePost({{ $post->id }});">
                                            <i class="material-icons">delete</i>
                                        </button>
                                        <form action="{{ route('admin.post.destroy', $post->id) }}" id="delete-form-{{ $post->id }}" method="POST">
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
        function deletePost(id){
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
                    document.getElementById('delete-form-'+id).submit();
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
