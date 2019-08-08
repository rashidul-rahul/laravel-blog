@extends('layouts.backend.app')

@section('title', 'tag')

@push('css')
    <link href="{{ asset('assets/backend/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="block-header">
{{--            <h2>Add New Label</h2>--}}
        </div>
        <!-- Vertical Layout | With Floating Label -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Edit Tag
                        </h2>
                    </div>
                    <div class="body">
                        <form action="{{ route('admin.tag.update', $tag->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" id="email_address" name="name" class="form-control" value="{{ $tag->name }}">
                                    <label class="form-label">Tag Name</label>
                                </div>
                            </div>

                            <input type="checkbox" id="remember_me_2" class="filled-in">
                            <br>
                            <a class="btn btn-primary m-t-15 waves-effect" href="{{ route('admin.tag.index') }}">BACK</a>
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">UPDATE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Vertical Layout | With Floating Label -->
    </div>
@endsection

@push('js')

@endpush
