<?php
namespace CupaTest\Models;

use App;
use Config;
use Cupa\Models\User;
use Cupa\Models\UserRequirement;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRequirementModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;

    public function testFetchOrCreateRequirements()
    {
        $user = factory(User::class)->create();
        $year = date('Y');

        $this->assertTrue(empty(UserRequirement::where('user_id', '=', $user->id)->first()));

        $userRequirements = UserRequirement::fetchOrCreateRequirements($user->id, $year);

        $this->assertFalse(empty(UserRequirement::where('user_id', '=', $user->id)->first()));
    }

    public function testUpdateRequirements()
    {
        $user = factory(User::class)->create();
        $year = date('Y');
        $expectedData = ['test' => 'yup yup'];

        UserRequirement::updateRequirements($user->id, $year, $expectedData);
        $userRequirements = UserRequirement::where('user_id', '=', $user->id)
            ->where('year', '=', $year)->first();

        $this->assertEquals($expectedData, json_decode($userRequirements->requirements, true), 'it has the correct data');
    }

}
