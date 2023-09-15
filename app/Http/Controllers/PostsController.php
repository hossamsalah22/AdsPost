<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Arr;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->paginate(10);

        return responseJson($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {
        $validate = $request->validated();
        $image = Arr::pull($validate, 'image');
        $data['title'] = Arr::pull($validate, 'title');
        $data['description'] = Arr::pull($validate, 'description');
        $data['phone'] = ltrim(Arr::pull($validate, 'phone'), '0');
        $data['user_id'] = auth('api')->user()->id;

        $post = Post::create($data);

        $image && $post->addMedia($image)->toMediaCollection('posts');

        return responseJson($post,null,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post = $post->with('user')->find($post->id);
        return responseJson($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request,Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return responseJson([]);
    }
}
