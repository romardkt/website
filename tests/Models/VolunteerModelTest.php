<?php
namespace CupaTest\Models;

use App;
use Cupa\Models\User;
use Cupa\Models\Volunteer;
use Cupa\Models\UserProfile;
use Cupa\Models\VolunteerEvent;
use Cupa\Models\VolunteerEventSignup;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class VolunteerModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;


    public function testUser()
    {
        // create a volunteer
        $user = factory(User::class)->create();
        $volunteer = factory(Volunteer::class)->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $volunteer->user->id, 'it has a user relation');
    }

    public function testSignups()
    {
        // create a volunteer
        $user = factory(User::class)->create();
        $volunteer = factory(Volunteer::class)->create(['user_id' => $user->id]);

        $this->assertEquals(0, count($volunteer->signups), 'the user does not have any events');

        // create the event and signup the volunteers
        $volunteerEvent = factory(VolunteerEvent::class)->create();
        $volunteerEventSignup = factory(VolunteerEventSignup::class)->create([
          'volunteer_event_id' => $volunteerEvent->id,
          'volunteer_id' => $volunteer->id,
        ]);

        $this->assertEquals(1, count($volunteer->signups()), 'the user now has one signup');

    }

    public function testFetchAllVolunteers()
    {
      // create the users and volunteers
      factory(User::class, 15)->create()
          ->each(function($user) {
            factory(UserProfile::class)->create(['user_id' => $user->id]);
            factory(Volunteer::class)->create(['user_id' => $user->id]);
          });

      // get the data and test the count
      $volunteers = Volunteer::fetchAllVolunteers();
      $this->assertEquals(15, count($volunteers), 'it has the correct number of volunteers');
    }

    public function testFetchAllVolunteersForDownload()
    {
      // create the users and volunteers
      factory(User::class, 15)->create()
          ->each(function($user) {
            factory(UserProfile::class)->create(['user_id' => $user->id]);
            factory(Volunteer::class)->create(['user_id' => $user->id]);
          });

      // get the data and test the count
      $volunteers = Volunteer::fetchAllVolunteersForDownload();

      $this->assertTrue(is_array($volunteers), 'an array is returned');
      $this->assertEquals(16, count($volunteers), 'it has the correct number of volunteers (plus header)');
    }
}