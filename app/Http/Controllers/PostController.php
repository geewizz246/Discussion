<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Post;
use App\Attachment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Get a validator for an incoming discussion create/update request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // 'description' => ['required', 'max:240'],
            'post' => ['required'],
            'attachments.*' => ['file', 'mimes:txt,doc,docx,xls,xlsx,txt,pdf,jpg,jpeg,png,bmp,svg,gif,jfif', 'max:5120'],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Get the discussion in which the post will be done.
        $discussion = Discussion::findOrFail($request->discussion);

        // Create a post.
        $post = new Post;

        return view('post.create')
            ->with('discussion', $discussion)
            ->with('post', $post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request.
        $this->validator($request->all())->validate();

        // Get the corresponding discussion.
        $discussion = Discussion::findOrFail($request->discussion);

        // Create the post.
        $post = $discussion->posts()->create([
            'user_id' => Auth::id(),
            'discussion_id' => $discussion->id,
            'body' => $request->post,
            // is_reply defaults to true
        ]);

        // Handle any attachments that may exist.
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // error_log($file->getClientOriginalName()); // Debugging
                
                // Store the file on the public disk.
                $path = $file->storeAs("discussions/$discussion->id/user/" . Auth::id(), str_replace(' ', '_', $file->getClientOriginalName()), 'public');
                
                // Create the attachment record in the database.
                $post->attachments()->create([
                    'path' => $path
                ]);
            }
        }

        return redirect(route('discussion.show', ['discussion' => $discussion->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        // Get the discussion where the post to be edited is.
        $discussion = Discussion::findOrFail($request->discussion);

        // Find the post to edit.
        $post = $discussion->posts->where('id', $id)->first();

        // Check to see if this user owns this post.
        if (Gate::denies('isMyPost', $post)) {
            abort(404, "Sorry, you don't have permission to do that.");
        }

        // Save previous URL for redirecting.
        if (URL::previous() !== URL::current() && !Session::has('errors')) {
            Session::put('url.intended', URL::previous());
        }

        // Direct the user to the edit post form.
        return view('post.edit')
            ->with('discussion', $discussion)
            ->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        // Validate the request.
        $this->validator($request->all())->validate();
        
        // Get the discussion where the post to be edited is.
        $discussion = Discussion::findOrFail($request->discussion);

        // Find the post to edit.
        $post = $discussion->posts->where('id', $id)->first();

        // Check to see if this user owns this post.
        if (Gate::denies('isMyPost', $post)) {
            abort(404, "Sorry, you don't have permission to do that.");
        }

        // Edit the post with request data.
        $post->body = $request->post;

        // Handle any attachments that may exist.
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // error_log($file->getClientOriginalName()); // Debugging

                // Store the file on the public disk.
                $path = $file->storeAs("discussions/$discussion->id/user/" . Auth::id(), str_replace(' ', '_', $file->getClientOriginalName()), 'public');
                
                // If path does not match an existing record in the database... 
                if (Attachment::where('path', $path)->get()->isEmpty()) {
                    // Create the attachment record in the database.
                    $post->attachments()->create([
                        'path' => $path
                    ]);
                }
            }
        }

        // Update post and its corresponding attachments.
        $post->save();

        // Redirect the user to the previous page in their session.
        return Redirect::intended('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
