<?php
namespace CupaTest\Models;

use App;
use Config;
use Cupa\User;
use Cupa\Role;
use Cupa\UserRole;
use Cupa\Volunteer;
use Cupa\UserWaiver;
use Cupa\UserProfile;
use Cupa\UserContact;
use Cupa\UserRequirement;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;

    public function testUserHasChildren()
    {
        // create the parent and minor account
        $user = factory(User::class)->create();
        $minor = factory(User::class)->make([
          // 'parent' => $user->id,
          'email' => null,
          'password' => null
        ]);
        $user->children()->save($minor);

        // test the relation
        $this->assertNotNull($minor->parentObj, 'the parent object is not null');
        $this->assertEquals($user->id, $minor->parentObj->id, 'the parent object the correct one');

        $this->assertNotNull($user->children, 'the parent object has children');
        $this->assertEquals(1, $user->children()->get()->count(), 'the parent has a single child');

        // create a second child
        $anotherMinor = factory(User::class)->make([
          // 'parent' => $user->id,
          'email' => null,
          'password' => null
        ]);
        $user->children()->save($anotherMinor);

        $this->assertEquals($user->id, $anotherMinor->parentObj->id, 'the parent object the correct one');
        $this->assertEquals(2, $user->children()->get()->count(), 'the parent now has a two children');
    }

    public function testUserHasRolesObject()
    {
        // create the roles
        $adminRole = factory(Role::class)->create(['name' => 'admin']);
        $managerRole = factory(Role::class)->create(['name' => 'manager']);

        // create an admin user
        $user = factory(User::class)->create();
        $adminUserRole = factory(UserRole::class)->make([
          'role_id' => $adminRole->id,
        ]);
        $user->roles()->save($adminUserRole);

        // create a manager user
        $anotherUser = factory(User::class)->create();
        $managerUserRole = factory(UserRole::class)->make([
          'role_id' => $managerRole->id,
        ]);
        $anotherUser->roles()->save($managerUserRole);

        // test the relation
        $this->assertEquals(count($user->roles), 1, 'the user has a single role');
        $this->assertEquals(count($anotherUser->roles), 1, 'the anotherUser has a single role');

        // test the roles have the correct values
        $this->assertTrue($user->roles->contains($adminUserRole), 'the user roles has a role of admin');
        $this->assertTrue($anotherUser->roles->contains($managerUserRole), 'the anotherUser roles has a role of manager');

        // test the roles do not have the other values
        $this->assertFalse($user->roles->contains($managerUserRole), 'the user roles does not have a manager');
        $this->assertFalse($anotherUser->roles->contains($adminUserRole), 'the anotherUser roles does not have an admin');
    }

    public function testUserHasAProfile()
    {
        // create the user and profile
        $user = factory(User::class)->create();
        $user->profile()->save(factory(UserProfile::class)->make());

        // test the relation
        $this->assertNotNull($user->profile, 'the profile object is not null');
    }

    public function testUserHasContacts()
    {
        // create the user and profile
        $user = factory(User::class)->create();
        $user->contacts()->save(factory(UserContact::class)->make());
        $user->contacts()->save(factory(UserContact::class)->make());

        // test the relation
        $this->assertNotNull($user->contacts, 'the contacts object is not null');
        $this->assertEquals(count($user->contacts), 2, 'the user has two contacts');
    }

    public function testUserMightHaveAVolunteer()
    {
        // create the user and volunteer
        $user = factory(User::class)->create();
        $user->volunteer()->save(factory(Volunteer::class)->make());

        // test the relation
        $this->assertNotNull($user->volunteer, 'the volunteer object is not null');

        // create a user with no volunteer
        $anotherUser = factory(User::class)->create();

        // test the relation
        $this->assertNull($anotherUser->volunteer, 'the volunteer object is null');
    }

    public function testUserHasABalance()
    {
        // create the user and profile
        $user = factory(User::class)->create();

        // test the relation
        $this->assertEquals($user->balance(), 0, 'the balance is zero');
    }

    public function testUserFullname()
    {
        $user = factory(User::class)->create();

        $this->assertEquals($user->fullname(), $user->first_name.' '.$user->last_name, 'the fullname combines the first and last name correctly');
    }

    public function testUserSlug()
    {
        $user = factory(User::class)->create();

        $this->assertEquals($user->slug(), str_slug($user->fullname()), 'this slug is generated successfully');
    }

    public function testUserIsVolunteer()
    {
        $user = factory(User::class)->create();

        $this->assertEquals($user->isVolunteer(), false, 'the user is not a volunteer');

        $user->volunteer()->save(factory(Volunteer::class)->make());
        $this->assertEquals($user->isVolunteer(), true, 'the user is now a volunteer');
    }

    public function testUserHasWaiver()
    {
        $user = factory(User::class)->create();

        $this->assertEquals($user->hasWaiver(2011), false, 'the user does not have a waiver');
        $this->assertEquals($user->hasWaiver(), false, 'the user does not have a waiver');

        factory(UserWaiver::class)->create([
            'user_id' => $user->id,
            'year' => 2011,
        ]);

        $this->assertEquals($user->hasWaiver(2011), true, 'the user does have a waiver');
        $this->assertEquals($user->hasWaiver(), false, 'the user does not have a waiver');

        factory(UserWaiver::class)->create([
            'user_id' => $user->id,
            'year' => date('Y'),
        ]);

        $this->assertEquals($user->hasWaiver(2011), true, 'the user does have a waiver');
        $this->assertEquals($user->hasWaiver(), true, 'the user does have a waiver');
    }

    public function testUserProfileComplete()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(false, $user->profileComplete(), 'the user profile is not complete and missing');

        $userProfile = factory(UserProfile::class)->create([
            'user_id' => $user->id,
            'phone' => null,
            'nickname' => null,
            'height' => null,
            'level' => null,
            'experience' => null,
        ]);

        // update all fields
        foreach(['phone' => '555-555-5555', 'nickname' => 'Test', 'height' => 70, 'level' => 'College', 'experience' => 2001] as $prop => $value) {
            $this->assertEquals(false, $user->profileComplete(), 'the user profile is not complete');
            $user->profile->$prop = $value;
            $user->profile->save();
        }

        // check that it is now complete
        $this->assertEquals(true, $user->profileComplete(), 'the user profile is now complete');
    }

    public function testUserCoachingRequirements()
    {
        $user = factory(User::class)->create();

        $this->assertNull($user->coachingRequirements(2011), 'the user has no coaching requirements for 2011');
        $this->assertNull($user->coachingRequirements(), 'the user has no coaching requirements for current year');

        $userReqs = factory(UserRequirement::class)->create([
            'user_id' => $user->id,
            'year' => 2011,
            'requirements' => '{}',
        ]);

        $this->assertNotFalse($user->coachingRequirements(2011), 'the user now has coaching requirements for 2011');
        $this->assertNull($user->coachingRequirements(), 'the user has no coaching requirements for current year');

        $userReqs = factory(UserRequirement::class)->create([
            'user_id' => $user->id,
            'year' => date('Y'),
            'requirements' => '{}',
        ]);

        $this->assertNotFalse($user->coachingRequirements(2011), 'the user now has coaching requirements for 2011');
        $this->assertNotFalse($user->coachingRequirements(), 'the user now has coaching requirements for current year');
    }

    public function testUserTypeahead()
    {
        // create users and sort by last_name -> first_name
        $users = factory(User::class, 10)->create();
        $single = $users[0];
        $users->sortBy(['last_name', 'first_name']);

        $this->assertEquals(User::typeahead(null), [], 'empty filter returns empty array');

        $this->assertTrue(count(User::typeahead($single->first_name)) > 0, 'filtering on a known first name returns at least one user');
        $this->assertTrue(count(User::typeahead($single->fullname())) === 1, 'filtering on a known fullname returns single record');

        // build the expected result
        $singleResponse = [['id' => $single->id, 'text' => $single->fullname() . ' (' . $single->email . ')']];
        $this->assertEquals($singleResponse, User::typeahead($single->id, true), 'filtering on single id returns that user');

        // build the expected results
        $multiResponse = [];
        $multiIds = [];
        foreach($users as $user) {
            $multiResponse[] = [
                'id' => $user->id,
                'text' => $user->fullname() . ' (' . $user->email . ')',
            ];

            $multiIds[] = $user->id;

            if (count($multiIds) === 3) {
                break;
            }
        }

        $this->assertEquals($multiResponse, User::typeahead(implode(',', $multiIds), true), 'filtering on many ids returns those users', $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertTrue(count(User::typeahead($single->email, false, true)) === 1, 'filtering on a known email returns single record');
    }

    public function testGenerateCode()
    {
        $code = User::generateCode('activation_code');

        $this->assertNotNull($code, 'the generated code is not null');
        $this->assertEquals(strlen($code), 25, 'the generated code has the right length');

        $code = User::generateCode('activation_code', 13);
        $this->assertNotNull($code, 'the generated code is not null');
        $this->assertEquals(strlen($code), 13, 'the generated code has the right length');
    }

    public function testIsUniqueAndGenerateCode()
    {
        $users = factory(User::class, 10)->create();

        foreach($users as $user) {
            $this->assertFalse(User::isUnique('activation_code', $user->activation_code), 'expected code is not unique');
        }

        $code = User::generateCode('activation_code');
        $this->assertTrue(User::isUnique('activation_code', $code), 'the new code is unique');
    }

    public function testCheckForDuplicate()
    {
        $user = factory(User::class)->create();

        $this->assertNull(User::checkForDuplicate([
            'first_name' => 'No in DB',
            'last_name' => 'No in DB',
            'birthday' => '1980-10-10',
        ]), 'there is no duplicate');

        $this->assertNotNull(User::checkForDuplicate([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'birthday' => $user->birthday->format('Y-m-d'),
        ]), 'there is should be a duplicate duplicate');
    }

    public function testFetchAllDuplicates()
    {
        // create users
        $users = factory(User::class, 15)->create();

        // create duplicate user
        $duplicateUser = factory(User::class)->create([
            'first_name' => $users[0]->first_name,
            'last_name' => $users[0]->last_name,
        ]);

        $allDuplicateUsers = User::fetchAllDuplicates();
        $keys = array_keys($allDuplicateUsers);

        $this->assertEquals(1, count($allDuplicateUsers), 'there is only one duplicate user');
        $this->assertEquals(2, count($allDuplicateUsers[$keys[0]]), 'there is user data for both duplicates');
        $this->assertEquals($allDuplicateUsers[$keys[0]][0]->fullname(), $allDuplicateUsers[$keys[0]][1]->fullname(), 'make sure that the names are the same between the two');
    }
}
