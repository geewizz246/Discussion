<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect(route('discussion.index'));
        // return view('home');
    }

    /**
     * Show the user's profile page.
     */
    public function profile()
    {
        // Get all of the user's discussions.
        $discussions = Auth::user()->discussions()->paginate(10);
        
        return view('user.profile-user')
            ->with('discussions', $discussions);
    }

    /**
     * Run a search on all of the discussions in the user's created discussions.
     */
    public function searchUserDiscussions(Request $request)
    {
        // Extract query from request.
        $query = $request['query'];

        // Find the discussions that match the query.
        $matchingDiscussions = Auth::user()->discussions()->where('topic', 'LIKE', "%$query%")->paginate(10);

        // Get query input from request to return as old input.
        $request->flashOnly(['query']);

        return view('user.profile-user')
            ->with('discussions', $matchingDiscussions);
    }
}
