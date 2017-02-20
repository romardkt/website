<?php
namespace CupaTest\Models;

use App;
use Config;
use Cupa\Models\User;
use Cupa\Models\UserMedicalRelease;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class UserMedicalReleaseModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;

    public function testUser()
    {
        $user = factory(User::class)->create();
        $release = factory(UserMedicalRelease::class)->create([
          'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $release->user->id, 'it has the user relation');
    }

    public function testUpdateOrCreateRelease()
    {
        $user = factory(User::class)->create();
        $year = date('Y');
        $data = json_encode(['just' => 'testing']);

        // create new release
        $release = UserMedicalRelease::updateOrCreateRelease($user, $year, $user->id, $data);

        $this->assertEquals($year, $release->year, 'it has the correct year');
        $this->assertEquals($user->id, $release->updated_by, 'it has the correct updated by');
        $this->assertEquals($data, $release->data, 'it has the correct data');

        // update the data
        $updatedData = json_encode(['more' => 'testing', 'second' => 'item']);
        $release = UserMedicalRelease::updateOrCreateRelease($user, $year, $user->id, $updatedData);
        $this->assertEquals($updatedData, $release->data, 'it has the correct data on update');
    }

    public function testFetchRelease()
    {
        $user = factory(User::class)->create();
        $year = date('Y');
        $data = json_encode(['just' => 'testing']);

        // create new release
        $release = UserMedicalRelease::updateOrCreateRelease($user, $year, $user->id, $data);

        $fetchedRelease = UserMedicalRelease::fetchRelease($user->id, $year);
        $this->assertEquals($release->user_id, $fetchedRelease->user_id, 'you can get the correct release user');
        $this->assertEquals($release->year, $fetchedRelease->year, 'you can get the correct release year');
        $this->assertEquals($release->data, $fetchedRelease->data, 'you can get the correct release data');
    }
}
