<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
class PostController extends Controller
{

    protected $validation = [
        'title' => 'required|string|max:100',
        'content' => 'required',
        'published' => 'sometimes|accepted',
        'category_id' => 'nullable|exists:categories,id',
        'tags' => 'nullable|exists:tags,id',
        'image' => 'nullable|image|max:2048'
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $request->validate($this->validation);

        $newPost = new Post();
        $newPost->title = $data['title'];
        $newPost->content = $data['content'];
        $newPost->published = isset($data['published']);
        $newPost->category_id = $data['category_id'];

        $slug = Str::of($newPost->title)->slug('-');
        $count = 1;
        while(Post::where('slug', $slug)->first()) {
            $slug = Str::of($newPost->title)->slug('-').'-'.$count;
            $count++;
        }

        if(isset($data['image'])) {
            $path = Storage::put('uploads', $data['image']);
            $newPost->image = $path;
        }

        $newPost->slug = $slug;
        $newPost->save();

        if(isset($data['tags'])) {
            $newPost->tags()->sync($data['tags']);
        }

        return redirect()->route('posts.show', $newPost->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::where('slug', $id)->first();
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $post = Post::where('slug', $id)->first();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
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
        $data = $request->all();
        $post = Post::where('slug', $id)->first();

        $request->validate($this->validation);

        if($data['title'] != $post->title) {
            $post->title = $data['title'];
            $slug = Str::of($data['title'])->slug('-');
            $count = 1;

            if($slug != $post->slug) {
                while(Post::where('slug', $slug)->first()) {
                    $slug = Str::of($data['title'])->slug('-').'-'.$count;
                    $count++;
                }
            }

            $post->slug = $slug;
            
        }

        if(isset($data['image'])) {
            Storage::delete($post->image);
            $path = Storage::put('uploads', $data['image']);
            $post->image = $path;
        }

        $post->content = $data['content'];
        $post->published = isset($data['published']);
        $post->category_id = $data['category_id'];
        $post->save();

        if(isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return redirect()->route('posts.show', $post->slug);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::where('slug', $id)->first();

        if(isset($post->image)) {
            Storage::delete($post->image);
        }

        $post->delete();

        if(URL::previous() == 'http://127.0.0.1:8000/admin/posts/'.$id) {
            return redirect()->route('posts.index');
        } 
    }
}
