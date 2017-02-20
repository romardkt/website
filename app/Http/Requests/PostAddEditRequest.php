<?php

namespace Cupa\Http\Requests;

use Cupa\Models\Post;
use Gate;

class PostAddEditRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('is-reporter');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $post = Post::fetchBySlug($this->get('slug'));
        $postId = null;
        if ($post) {
            $postId = ','.$post->id;
        }

        return [
            'category' => 'required|not_in:0',
            'title' => 'required',
            'slug' => 'required|unique:posts,slug'.$postId,
            'content' => 'required',
            'post_at_date' => 'required|date',
            'post_at_time' => 'required|regex:/1?[0-9]:[0-9][0-9]/',
            'remove_at_date' => 'date',
            'remove_at_time' => 'regex:/1?[0-9]:[0-9][0-9]/',
            'pdf' => 'mimes:jpg,jpeg,png,gif',
        ];
    }
}
