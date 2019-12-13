<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Post;
use App\Attachment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;

class DiscussionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'search']);
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
            'topic' => ['required', 'string', 'regex:/^[a-zA-Z][a-zA-Z0-9\-\$\&\.\ ]{5,}$/i', 'max:120'],
            'description' => ['required', 'max:240'],
            'post' => ['required'],
            'attachments.*' => ['file', 'mimes:txt,doc,docx,xls,xlsx,txt,pdf,jpg,jpeg,png,bmp,svg,gif,jfif', 'max:5120'],
        ]);
    }

    /**
     * File types for handling icons.
     */
    protected $icons = [
        'pdf' => 'pdf',
        'doc' => 'word',
        'docx' => 'word',
        'xls' => 'excel',
        'xlsx' => 'excel',
        'ppt' => 'powerpoint',
        'pptx' => 'powerpoint',
        'txt' => 'text',
        'png' => 'image',
        'jpg' => 'image',
        'jpeg' => 'image',
        'bmp' => 'image', 
        'svg' => 'image',
        'gif' => 'image',
        'jfif' => 'image',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all of the discussions.
        $discussions = Discussion::orderBy('created_at', 'DESC')->paginate(10);

        // Direct the user to the discussions index.
        return view('discussion/index')->with('discussions', $discussions);
        
        return view('discussion.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Create a new discussion
        $discussion = new Discussion;

        // Direct the user to the create discussion form.
        return view('discussion/create')
            ->with('discussion', $discussion);
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
        
        // Create the discussion and associate it with the user.
        $discussion = Auth::user()->discussions()->create([
            'topic' => $request->topic,
            'description' => $request->description,
        ]);

        // Create the initial post.
        $post = Post::create([
            'user_id' => Auth::id(),
            'discussion_id' => $discussion->id,
            'body' => $request->post,
            'is_reply' => false,
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

        return redirect(route('discussion.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the discussion.
        $discussion = Discussion::findOrFail($id);

        // Get all of its associated posts.
        $posts = $discussion->posts()->orderBy('created_at')->paginate(10);
        
        return view('discussion/show')
            ->with('discussion', $discussion)
            ->with('posts', $posts)
            ->with('icons', $this->icons);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discussion = Discussion::findOrFail($id);

        // Check to see if this user owns this discussion.
        if (Gate::denies('isMyDiscussion', $discussion)) {
            abort(404, "Sorry, you don't have permission to do that.");
        }

        // Save previous URL for redirecting.
        if (URL::previous() !== URL::current() && !Session::has('errors')) {
            Session::put('url.intended', URL::previous());
        }

        // Direct the user to the edit discussion form.
        return view('discussion/edit')->with('discussion', $discussion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request.
        $this->validator($request->all())->validate();
        
        // Find the discussion
        $discussion = Discussion::findOrFail($id);

        // Check to see if this user owns this discussion.
        if (Gate::denies('isMyDiscussion', $discussion)) {
            abort(404, "Sorry, you don't have permission to do that.");
        }

        // Edit the discussion with request data.
        $discussion->topic = $request->topic;
        $discussion->description = $request->description;

        // Edit the discussion post with request data.
        $post = $discussion->getOriginalPost();
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

        // Update discussion and its corresponding post and attachments.
        $discussion->save();
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
        // Find the discussion.
        $discussion = Discussion::findOrFail($id);

        // Check to see if this user owns this discussion.
        if (Gate::denies('isMyDiscussion', $discussion)) {
            abort(404, "Sorry, you don't have permission to do that.");
        }

        // Delete the discussion and all of the child posts and attachments.
        $discussion->delete();

        // Redirect the user to their profile.
        return redirect(route('user.profile'));
    }

    /**
     * Run a search on all of the discussions in the discussion forum.
     */
    public function search(Request $request)
    {
        // Extract query from request.
        $query = $request['query'];

        // Find the discussions that match the query.
        $matchingDiscussions = Discussion::where('topic', 'LIKE', "%$query%")->paginate(10);

        // Get query input from request to return as old input.
        $request->flashOnly(['query']);

        return view('discussion.index')
            ->with('discussions', $matchingDiscussions);
    }
}
