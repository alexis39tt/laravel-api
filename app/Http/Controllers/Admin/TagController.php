<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $request->validate([
            'name' => 'required|unique:tags|max:100'
        ]);

        $newTag = new Tag();
        $newTag->name = $data['name'];
        $newTag->slug = Str::of($newTag->name)->slug('-');
        $newTag->save();

        return redirect()->route('tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $data = $request->all();

        $request->validate([
            'name' => "required|unique:tags,name,{$tag->id}|max:100"
        ]);

        $tag->name = $data['name'];
        $tag->slug = Str::of($tag->name)->slug('-');
        $tag->save();

        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        if(URL::previous() == 'http://127.0.0.1:8000/admin/tags/'.$tag->id) {
            return redirect()->route('tags.index');
        } 
    }
}
