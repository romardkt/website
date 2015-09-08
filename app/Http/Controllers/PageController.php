<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\ContactRequest;
use Cupa\Http\Requests\LocationAddRequest;
use Cupa\League;
use Cupa\Pickup;
use Cupa\Post;
use Cupa\Tournament;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

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

    public function locationAdd(LocationAddRequest $request)
    {
        $input = $request->all();

        $location = Location::fetchOrCreateLocation($input);
        if ($location) {
            return response()->json(['status' => 'ok', 'name' => $location->name, 'value' => $location->id]);
        }

        return response()->json(['status' => 'error', 'message' => 'Unkown Error']);
    }
}
