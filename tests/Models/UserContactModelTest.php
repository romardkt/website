<?php
namespace CupaTest\Models;

use App;
use Config;
use Cupa\Models\User;
use Cupa\Models\UserContact;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class UserContactModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;

    public function testHasContact()
    {
        $user = factory(User::class)->create();
        $contact = factory(UserContact::class)->create([
          'user_id' => $user->id,
        ]);

        $user2 = factory(User::class)->create();

        $this->assertTrue(UserContact::hasContact($user->id, $contact->name, $contact->phone), 'it finds the contact within the user');
        $this->assertFalse(UserContact::hasContact($user2->id, $contact->name, $contact->phone), 'it should not find the contact within the user');
    }
}
