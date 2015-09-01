<?php

namespace Cupa\Http\Controllers;

use Cupa\Post;
use Cupa\League;
use Cupa\Tournament;
use Cupa\Pickup;

class PageController extends Controller
{
    public function home()
    {
        $posts = Post::fetchPostsForHomePage(8);
        $featured = Post::fetchPostsForHomePage(null, true);
        $leagues = League::fetchAllLeaguesForHomePage();
        $tournaments = Tournament::fetchAllCurrent();
        $pickups = Pickup::fetchAllPickups(true);

        return view('page.home', compact('posts', 'featured', 'leagues', 'tournaments', 'pickups'));
    }
}
