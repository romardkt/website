<?php
namespace CupaTest\Models;

use App;
use Cupa\Models\Volunteer;
use Cupa\Models\VolunteerEvent;
use Cupa\Models\VolunteerEventSignup;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class VolunteerEventSignupModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;


    public function testEvent()
    {
        // create the signup
        $event = factory(VolunteerEvent::class)->create();
        $signup = factory(VolunteerEventSignup::class)->create([
          'volunteer_event_id' => $event->id
        ]);

        $this->assertEquals($event->id, $signup->event->id, 'it has a volunteer event relation');
    }

    public function testVolunteer()
    {
        // create the volunteer
        $volunteer = factory(Volunteer::class)->create();
        $signup = factory(VolunteerEventSignup::class)->create([
          'volunteer_id' => $volunteer->id
        ]);

        $this->assertEquals($volunteer->id, $signup->volunteer->id, 'it has a volunteer relation');
    }
}