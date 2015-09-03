<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\ContactRequest;
use Cupa\Post;
use Cupa\League;
use Cupa\Tournament;
use Cupa\Pickup;
use Mail;
use Session;
use App;

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

    public function contact()
    {
        return view('page.contact');
    }

    public function postContact(ContactRequest $request)
    {
        $contactInformation = $request->all();

        Mail::send('emails.contact', array('data' => $contactInformation), function ($m) use ($contactInformation) {
            if (App::environment() == 'prod') {
                $m->to('webmaster@cincyultimate.org', 'CUPA Webmaster')
                  ->to('cincyultimate@gmail.com', 'CUPA Contact');
            } else {
                $m->to('kcin1018@gmail.com');
            }

            $m->subject($contactInformation['subject'])
              ->replyTo($contactInformation['from_email'], $contactInformation['from_name']);
        });

        Session::flash('msg-success', 'Message has been sent');

        return redirect()->route('contact');
    }
}
