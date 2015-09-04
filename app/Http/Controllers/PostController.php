<?php

namespace Cupa\Http\Controllers;

use Illuminate\Http\Request;
use Cupa\Http\Requests\PostAddEditRequest;
use Cupa\Post;
use Config;
use Session;
use Auth;
use Image;

class PostController extends Controller
{
    public function all()
    {
        $posts = Post::fetchAllPosts();

        return view('post.all', compact('posts'));
    }

    public function view($slug)
    {
        $post = Post::fetchBySlug($slug);

        return view('post.view', compact('post'));
    }

    public function add(Request $request)
    {
        $categories = Config::get('cupa.postCategories');

        return view('post.add', compact('categories'));
    }

    public function postAdd(PostAddEditRequest $request)
    {
        $input = $request->all();

        $input['post_at'] = convertDate($input['post_at_date'].' '.$input['post_at_time']);
        $input['slug'] = str_slug(date('Y-m-d-', strtotime($input['post_at'])).$input['title']);
        $input['remove_at'] = (empty($input['remove_at_date'])) ? null : convertDate($input['remove_at_date'].' '.$input['remove_at_time']);

        $post = Post::create([
            'category' => $input['category'],
            'title' => $input['title'],
            'slug' => $input['slug'],
            'image' => (isset($input['is_featured'])) ? '/data/posts/default.png' : null,
            'link' => (empty($input['link'])) ? null : $input['link'],
            'content' => $input['content'],
            'posted_by' => Auth::user()->id,
            'post_at' => $input['post_at'],
            'remove_at' => $input['remove_at'],
            'is_featured' => (isset($input['is_featured'])) ? 1 : 0,
            'is_visible' => (isset($input['is_visible'])) ? 1 : 0,
        ]);

        if ($request->hasFile('image')) {
            $filePath = public_path().'/data/posts/'.time().'-'.$post->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath, $request) {
                    return $image->make($request->file('image')->getRealPath())->resize(800, 400)->orientate()->save($filePath);
                });
            $post->image = str_replace(public_path(), '', $filePath);
            $post->save();
        }

        Session::flash('msg-success', 'New Post created');

        return redirect()->route('post_view', $post->slug);
    }

    public function edit($slug)
    {
        $categories = Config::get('cupa.postCategories');
        $post = Post::fetchBySlug($slug);

        return view('post.edit', compact('categories', 'post'));
    }

    public function postEdit(PostAddEditRequest $request, $slug)
    {
        $post = Post::fetchBySlug($slug);

        $input = $request->all();
        $input['post_at'] = convertDate($input['post_at_date'].' '.$input['post_at_time']);
        $input['slug'] = str_slug(date('Y-m-d-', strtotime($input['post_at_date'])).$input['title']);
        $input['remove_at'] = (empty($input['remove_at_date'])) ? null : convertDate($input['remove_at_date'].' '.$input['remove_at_time']);

        $post->category = $input['category'];
        $post->title = $input['title'];
        $post->slug = $input['slug'];
        $post->link = (empty($input['link'])) ? null : $input['link'];
        $post->content = $input['content'];
        $post->post_at = $input['post_at'];
        $post->remove_at = $input['remove_at'];
        $post->is_featured = (isset($input['is_featured'])) ? 1 : 0;
        $post->is_visible = (isset($input['is_visible'])) ? 1 : 0;

        if (!$request->hasFile('image') && isset($input['image_remove'])) {
            // remove image
            $filePath = public_path().$post->image;
            if ($post->image != '/data/posts/default.png' && file_exists($filePath)) {
                unlink($filePath);
            }
            $post->image = null;
        } elseif ($request->hasFile('image')) {
            $filePath = public_path().'/data/posts/'.time().'-'.$post->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath, $request) {
                    return $image->make($request->file('image')->getRealPath())->resize(800, 400)->orientate()->save($filePath);
                });
            $post->image = str_replace(public_path(), '', $filePath);
        }

        // assign default image for featured content
        if ($post->is_featured && $post->image === null) {
            $post->image = '/data/posts/default.png';
        }

        $post->save();

        Session::flash('msg-success', 'Post updated');

        return redirect()->route('post_view', $post->slug);
    }
}
