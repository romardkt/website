<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = [
        'category',
        'title',
        'slug',
        'image',
        'link',
        'content',
        'posted_by',
        'post_at',
        'remove_at',
        'is_featured',
        'is_visible',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * This will return all of the posts that are visible and have not been removed.
     *
     * @param int  $limit    The number of results to return
     * @param bool $featured Filter by featured flag
     *
     * @return Collection
     */
    public static function fetchPostsForHomePage($limit = null, $featured = false)
    {
        $now = Carbon::now();

        $select = static::with('postedBy')
            ->orderBy('post_at', 'desc')
            ->where(function ($query) use ($now) {
                $query->whereNull('remove_at')
                    ->orWhere('remove_at', '>=', $now);
            })
            ->where('is_visible', '=', 1)
            ->orderBy('post_at');

        // set the limit if passed in
        if (is_numeric($limit)) {
            $select->take($limit);
        }

        // limit by featured or not
        if ($featured) {
            $select->where('is_featured', '=', 1);
        } else {
            $select->where('is_featured', '=', 0);
        }

        // return the results
        return $select->get();
    }

    public static function fetchAllPosts($items = 10)
    {
        $posts = static::with('postedBy')
                     ->orderBy('post_at', 'desc');

        if (Auth::guest()) {
            $posts->where('is_visible', '=', 1);
        }

        return $posts->paginate($items);
    }

    public static function fetchBySlug($slug)
    {
        return static::with('postedBy')
            ->where('slug', '=', $slug)
            ->first();
    }
}
