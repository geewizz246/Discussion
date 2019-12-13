@extends('layouts.app')

@section('title', $user->username)
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 col-sm-5 col-lg-4">
                            <h2 class="card-title">{{ __($user->username . "'s Discussions") }}</h2>
                        </div>
                        <div class="col-md-5 col-sm-7 col-lg-6">
                            {{ Form::open([
                                'method' => 'GET', 'url' => route('user.discussion.search', ['username' => $user->username]),
                                'role' => 'search',
                            ]) }}
                                <div class="input-group">
                                    {{ Form::search('query', old('query'), [
                                        'placeholder' => 'Search discussions', 'class' => 'form-control',
                                    ]) }}
            
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    <table class="table table-condensed table-borderless table-hover">
                        <thead class="thead-light">
                            <th scope="col" class="h6 font-weight-bold">Topic</th>
                            <th scope="col" class="h6 font-weight-bold">Replies</th>
                            <th scope="col" class="h6 font-weight-bold">Last Post</th>
                        </thead>
                        <tbody>
                            @foreach ($discussions as $disc)
                                <tr class="table-row" onclick="{{ "document.location = '" . route('discussion.show', ['discussion' => $disc->id]) . "';"}}">
                                    <td>{{ $disc->topic }}</td>
                                    <td>{{ $disc->getNumOfReplies() }}</td>
                                    <td>{{ $disc->getLastReplyTime() }} by {{ $disc->getLastReplyAuthor() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-12 d-flex justify-content-center pt-3">
                            {{ $discussions->links() }}
                        </div>
                        <div class="col-12 d-flex justify-content-center pt-1">
                            Showing {{ $discussions->firstItem() . '-' . 
                            $discussions->lastItem() . ' discussions out of ' . $discussions->total() . '.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection