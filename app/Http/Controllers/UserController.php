<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Post;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function authenticate()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('authenticate');
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:4',
        ]);

        // Attempt to log in the user
        if (Auth::attempt(['email' => $validate['username'], 'password' => $validate['password']]) ||
            Auth::attempt(['name' => $validate['username'], 'password' => $validate['password']])) {
            // Authentication passed, redirect to home
            // return redirect()->route('home');
            return redirect()->intended('home');
        }else {
            // Authentication failed, redirect back with error
            return back()->withErrors([
                'auth' => 'The provided credentials do not match our records.',
            ]);
        }
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users', // Added unique validation
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Using Hash facade instead of bcrypt
        ]);

        Auth::login($user); // This sets up the session

        // Verify login was successful
        if (Auth::check()) {
            return redirect()->route('home'); // Redirect to home or dashboard
        }

        // Fallback in case login failed (shouldn't happen here)
        return back()->withErrors([
            'auth' => 'Registration successful but automatic login failed'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('authenticate');
    }

    public function home()
    {
        if (!Auth::check()) {
            return redirect()->route('authenticate');
        }
        // $posts = Post::all();
        // $posts = Post::leftjoin('comments', 'posts.id', '=', 'comments.post_id')
        //     ->select('posts.*', 'comments.commentcontent', 'comments.created_at as comment_created_at')
        //     ->orderBy('posts.created_at', 'desc')
        //     ->get();
        $posts = Post::with(['comments.user']) // Load comments and their authors
               ->orderBy('created_at', 'desc')
               ->get();
        dd($posts); // Debugging line to check posts data
        return view('home', compact('posts'));
        // return view('home');
    }
    public function storepost(Request $request)
    {
        try {
            // dd($request->all()); // Debugging line to check request data
            // Validate the request data
            $validate = $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'postcontent' => 'required|string|max:1000',
            ]);

            $data = Post::create($validate);

            if ($data) {
                return redirect()->route('home')->with('success', 'Post created successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to create post.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function storecomment(Request $request ,$post_id)
    {
        // dd($request->all(),$post_id,Auth::id()); // Debugging line to check request data
        $validate = $request->validate([
            'commentcontent' => 'required|string|max:1000',
        ]);
        $validate['user_id'] = Auth::id();
        $validate['post_id'] = $post_id;

        // dd($validate);

        $comment = Comments::create($validate);

        if (!$comment) {
            return redirect()->back()->with('error', 'Failed to add comment.');
        }

        return redirect()->route('home')->with('success', 'Comment added successfully!');
    }
}
