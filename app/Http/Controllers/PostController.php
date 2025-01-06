<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all posts
        $posts = Post::latest()->paginate(10);

        //render view with posts
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // write equivalent sql query to insert data
        //
        $sql = "insert into posts (picture, title, content, reporter, source) values ($picture, $title, $content, $reporter, $source)";

        $title = $request->title;


        $sql = "insert into posts (picture, title, content, reporter, source) values ($picture, $title, $content, $reporter, $source)";

        // Upload picture
        $picture = $request->file('picture');

        $picturePath = $picture->storeAs('posts', $picture->hashName(), 'public');
        Log::info('Picture:', ['picture' => $picture->hashName()]);

        // Create post
        Post::create([
            'picture' => $picture->hashName(),
            'title' => $request->title,
            'content' => $request->content,
            'reporter' => $request->reporter,
            'source' => $request->source
        ]);

        // Redirect to posts index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Get post by id
        $post = Post::findOrFail($id);
        // equivalent sql query:
        // select * from posts where id = $id

        // Render view with post
        return view('posts.show', compact('post'));
    }

    // public function show(Post $post)
    // {
    //     // Render view with post
    //     return view('posts.show', compact('post'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Render view with post
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        // Update post
        if ($request->hasFile('picture')) {
            // Upload picture
            $picture = $request->file('picture');
            $picture->storeAs('public/posts', $picture->hashName());

            //delete old image
            Storage::delete('public/posts/'.$post->image);

            $post->update([
                'picture' => $picture->hashName(),
                'title' => $request->title,
                'content' => $request->content,
                'reporter' => $request->reporter,
                'source' => $request->source
            ]);
        } else {
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'reporter' => $request->reporter,
                'source' => $request->source
            ]);
        }

        // Redirect to posts index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //delete image
        Storage::delete('public/posts/'. $post->image);

        // Delete post
        $post->delete();

        // Redirect to posts index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
