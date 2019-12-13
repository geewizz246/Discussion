@extends('layouts.app')

@section('title', __('Edit Post'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10">
            <div class="card">
                <div class="card-header">{{ __("Edit post to topic: $discussion->topic") }}</div>

                <div class="card-body">
                    {{ Form::open([
                        'method' => 'PUT', 'url' => route('post.update', ['post' => $post, 'discussion' => $discussion->id]),
                        'enctype' => 'multipart/form-data'
                    ]) }}
                        @csrf

                        @include('post.partials.post-form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
