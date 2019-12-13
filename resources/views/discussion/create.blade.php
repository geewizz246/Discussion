@extends('layouts.app')

@section('title', __('Create Discussion'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Create Discussion') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('discussion.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('discussion.partials.discussion-form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
