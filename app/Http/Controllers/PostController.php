<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // controller (method) posts
    public function index()
    {

        // get all posts
        $posts = Post::latest()->paginate(5);

        // render view with posts
        return view('posts.index', compact('posts'));
    }

    // controller (method) create
    public function create()
    {
        return view('posts.create');
    }

    // controller (method) store data in database
    public function store(Request $request)
    {

        // validate form
        /** memeriksa apakah data yang di input dari create.blade.php 
         *  sesuai dengan yang diharapkan/berdasarkan rule yg dibuat
         * 
         *  contohnya untuk image diberikan rule 
         * 
         *  image(required/diperlukan),
         *  image(dalam bentuk gambar dan jenis mimes(jenis file yang diizinkan) diantaranya jpeg,png,jpg,gif,svg),
         *  image(maxsize(maksimal ukuran file 2mb)
         */
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);

        // upload image

        // mendefinisikan request file image sebagai variabel $image dan mengambil isi dari tag image
        $image = $request->file('image');
        // membuat nama baru untuk image yang akan diupload ke storage/app/public/posts dengan nama hash(random string)
        $image->storeAs('public/posts', $image->hashName());

        // create post
        Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }


    // controller (method) edit
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }


    // controller (method) update
    public function update(Request $request, Post $post)
    {
        // validate form
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'

        ]);
        // check if image is uploaded
        if ($request->hasFile('image')) {
            // upload new image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());
            // delete old image
            Storage::delete('public/posts/' . $post->image);
            // update post with new image
            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'content' => $request->content
            ]);
        } else {
            // update post without image
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }
        // redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diubah!']);
    }


    // controller (method) destroy
    public function destroy(Post $post)
    {
        // delete image
        Storage::delete('public/posts/' . $post->image);
        // delete post
        $post->delete();
        // redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
