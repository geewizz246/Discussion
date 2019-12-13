@extends('layouts.app')

@section('title', __('Edit Discussion'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Edit Discussion') }}</div>

                <div class="card-body">
                    {{ Form::open([
                        'method' => 'PUT', 'url' => route('discussion.update', ['discussion' => $discussion->id]), 
                        'enctype' => 'multipart/form-data'
                    ]) }}
                        @csrf

                        @include('discussion.partials.discussion-form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
