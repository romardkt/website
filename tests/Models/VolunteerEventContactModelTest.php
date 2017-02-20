<?php
namespace CupaTest\Models;

use App;
use Cupa\User;
use Cupa\VolunteerEvent;
use Cupa\VolunteerEventContact;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class VolunteerEventContactModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;


    public function testUser()
    {
        // create the signup
        $user = factory(User::class)->create();
        $signup = factory(VolunteerEventContact::class)->create([
          'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $signup->user->id, 'it has a volunteer event relation');
    }

    public function testUpdateContacts()
    {
        $volunteerEvent = factory(VolunteerEvent::class)->create();
        $newContact = factory(User::class)->create();

        $this->assertEquals(0, $volunteerEvent->contacts->count(), 'it only has one contact');

        // update the contacts
        VolunteerEventContact::updateContacts($volunteerEvent->id, [$newContact->id]);
        $this->assertEquals(1, $volunteerEvent->contacts()->count(), 'it now has two contacts');
   }
}