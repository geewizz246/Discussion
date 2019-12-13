@extends('layouts.app')

@section('title', __('Create Post'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __("Replying to topic: $discussion->topic") }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('post.store', ['discussion' => $discussion->id]) }}" enctype="multipart/form-data">
                        @csrf

                        @include('post.partials.post-form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
