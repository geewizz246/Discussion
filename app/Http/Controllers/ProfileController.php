<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the user's profile page.
     */
    public function profile($username)
    {
        $user = User::where('username', $username)->first();

        if ($user !== NULL)
        {
            // Get all of the user's discussions.
            $discussions = $user->discussions()->paginate(10);

            if (Auth::id() === $user->id)
            {
                return view('user.profile-user')
                    ->with('discussions', $discussions);
            }
            else
            {
                return view('user.profile-guest')
                    ->with('user', $user)
                    ->with('discussions', $discussions);
            }
        }
        else
        {
            abort(404, "Sorry, the user does not exist.");
        }
    }

    /**
     * Run a search on all of the discussions in the user's created discussions.
     */
    public function searchUserDiscussions($username, Request $request)
    {
        $user = User::where('username', $username)->first();

        if ($user !== NULL)
        {
            // Extract query from request.
            $query = $request['query'];
    
            // Find the discussions that match the query.
            $matchingDiscussions = $user->discussions()->where('topic', 'LIKE', "%$query%")->paginate(10);
    
            // Get query input from request to return as old input.
            $request->flashOnly(['query']);

            if (Auth::id() === $user->id)
            {
        
                return view('user.profile-user')
                    ->with('discussions', $matchingDiscussions);
            }
            else
            {
                return view('user.profile-guest')
                    ->with('user', $user)
                    ->with('discussions', $matchingDiscussions);
            }
        }
        else
        {
            abort(404, "Sorry, the user does not exist");
        }
    }
}
