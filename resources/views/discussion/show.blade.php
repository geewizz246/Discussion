@extends('layouts.app')

@section('title', __($discussion->topic))
@section('content')
<div class="bg-white rounded container py-4 px-4">
    <!-- Discussion Initial Post -->
    <div class="page-header discussion-init">
        <div class="row">
            <div class="col-md-10">
                <h2 class="page-title">{{ $discussion->topic }}</h2>
                
                <!-- Post's user and date -->
                <h4 class="small pl-1">
                    Posted by 
                    <!-- Make username a link to user's profile -->
                    <a href="{{ route('user.profile', ['username' => $discussion->getCreator()]) }}">{{ $discussion->getCreator() }}</a>
                     - {{ date('d-M-Y @ h:i a', strtotime($discussion->getOriginalPost()->created_at)) }}

                    <!-- Handle Update Tags -->
                    @if ($discussion->getOriginalPost()->created_at != $discussion->getOriginalPost()->updated_at)
                        @if ($discussion->updated_at < $discussion->getOriginalPost()->updated_at)
                            {{ ' - Updated ' . date('d-M-Y @ h:i a', strtotime($discussion->getOriginalPost()->updated_at)) }}
                        @else
                            {{ ' - Updated ' . date('d-M-Y @ h:i a', strtotime($discussion->updated_at)) }}
                        @endif
                    @elseif ($discussion->created_at != $discussion->updated_at)
                        {{ ' - Updated ' . date('d-M-Y @ h:i a', strtotime($discussion->updated_at)) }}
                    @endif
                    <!-- END Handle Update Tags -->
                </h4>
            </div>
        </div>
        
        <div class="row">
            <div class="post-body col-md-8 ml-4">{{ $discussion->getOriginalPost()->body }}</div>
        </div>
        
        <!-- Handle Attachments -->
        <div class="row mt-2">
            @foreach($discussion->getOriginalPost()->attachments as $file)
                <div class="col-sm-2 text-center">
                    <i class="fa fa-file-{{ $icons[pathinfo($file->path, PATHINFO_EXTENSION)] }} fa-2x text-center"></i>
                    
                    <div class="clearfix"></div>

                    <a href="{{ Storage::disk('public')->url($file->path) }}" style="font-size: 10px;">
                        {{ Str::limit(pathinfo($file->path, PATHINFO_FILENAME), 10, '...') }}
                    </a>
                </div>
            @endforeach
        </div>
        <!-- END Handle Attachments -->
        
        <div class="row">
            <div class="col-md-6 pt-2 align-items-left">
                <a 
                    href="{{ route('post.create', ['discussion' => $discussion->id]) }}"
                    class="btn btn-primary mr-2"
                >
                    Post Reply
                </a>
                @auth
                    @if ($discussion->getCreator() === Auth::user()->username)
                        <a 
                            href="{{ route('discussion.edit', ['discussion' => $discussion->id]) }}"
                            class="btn btn-secondary ml-2"
                        >
                            Edit Discussion
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    <!-- END Discussion Intitial Post -->

    <!-- Subsequent Posts -->
    <div class="row">
        <div class="col-md-10">
            @foreach($posts as $post)
                <!-- Skip the first post i.e. the original post -->
                @if ($loop->first) @continue @endif
                <div class="card mt-3 bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 pt-2 card-subtitle">
                                
                                <!-- Post's user and date -->
                                <h6 class="small">
                                    <!-- Make username a link to user's profile -->
                                    <a href="{{ route('user.profile', ['username' => $post->getAuthor()]) }}">{{ $post->getAuthor() }}</a>
                                    - {{ date('d-M-Y @ h:i a', strtotime($post->created_at)) }}

                                    <!-- Handle Update Tags -->
                                    @if ($post->created_at != $post->updated_at)
                                        {{ ' - Updated ' . date('d-M-Y @ h:i a', strtotime($post->updated_at)) }}
                                    @endif
                                    <!-- END Handle Update Tags -->
                                </h6>
                            </div>

                            <!-- If current user is post author -->
                            @auth
                                @if ($post->getAuthor() === Auth::user()->username)
                                    <div class="col-md-4 text-right">
                                        <!-- Allow user to edit post -->
                                        <a 
                                            href="{{ route('post.edit', ['post' => $post->id, 'discussion' => $discussion->id]) }}"
                                            class="btn btn-sm btn-info ml-2 text-white"
                                        >
                                            Edit Post
                                        </a>
                                    </div>
                                @endif
                            @endauth
                        </div>
                        
                        <div class="row">
                            <div class="post-body px-4">{{ $post->body }}</div>
                        </div>

                        <!-- Handle Attachments -->
                        <div class="row mt-2">
                            @foreach($post->attachments as $file)
                                <div class="col-sm-2 text-center">
                                    <i class="fa fa-file-{{ $icons[pathinfo($file->path, PATHINFO_EXTENSION)] }} fa-2x text-center"></i>
                                    
                                    <div class="clearfix"></div>
                
                                    <a href="{{ Storage::disk('public')->url($file->path) }}" style="font-size: 10px;">
                                        {{ Str::limit(pathinfo($file->path, PATHINFO_FILENAME), 10, '...') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <!-- END Handle Attachments -->
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- END Subsequent Posts -->

    <div class="row">
        <div class="col-12 d-flex justify-content-center pt-3">
            {{ $posts->links() }}
        </div>
        <div class="col-12 d-flex justify-content-center pt-1">
            Showing {{ $posts->firstItem() . '-' . 
            $posts->lastItem() . ' posts out of ' . $posts->total() . '.' }}
        </div>
    </div>
</div>
@endsection
